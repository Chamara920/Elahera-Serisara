<?php

namespace App\Http\Controllers;

use App\Models\NewsEvent;
use App\Models\NewsEventComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class NewsEventController
 *
 * News and events. Community submissions require Admin approval.
 */
class NewsEventController extends Controller
{
    /**
     * Display approved news and events.
     */
    public function index(Request $request)
    {
        $type = $request->input('type'); // 'news' or 'event'

        $query = NewsEvent::with('user')
            ->where('is_approved', true);

        if ($type && in_array($type, ['news', 'event'])) {
            $query->where('type', $type);
        }

        $items = $query->latest('event_date')->paginate(8)->withQueryString();

        return view('news.index', compact('items', 'type'));
    }

    /**
     * Show single news / event item.
     */
    public function show($slug)
    {
        $item = NewsEvent::with(['user', 'comments.user'])
            ->where('slug', $slug)
            ->where(function ($q) {
                if (Auth::check() && (Auth::user()->isAdmin() || Auth::id() === NewsEvent::where('slug', request()->route('slug'))->value('user_id'))) {
                    // allow preview
                } else {
                    $q->where('is_approved', true);
                }
            })
            ->firstOrFail();

        $item->increment('views_count');

        $latestNews = NewsEvent::where('is_approved', true)
            ->where('id', '!=', $item->id)
            ->latest()
            ->take(3)
            ->get();

        return view('news.show', compact('item', 'latestNews'));
    }

    /**
     * Show submission form for news/event.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to submit news or events.');
        }

        return view('news.create');
    }

    /**
     * Store submitted news or event (requires admin approval).
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to submit news or events.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:news,event',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'content' => 'required|string|min:30',
            'featured_image' => 'nullable|image|max:3072',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('news', 'public');
        }

        $isApproved = Auth::user()->isAdmin();

        $item = NewsEvent::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
            'type' => $validated['type'],
            'event_date' => $validated['event_date'] ?? null,
            'location' => $validated['location'] ?? null,
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            'is_approved' => $isApproved,
        ]);

        if ($isApproved) {
            return redirect()->route('news.show', $item->slug)
                ->with('success', 'News/Event published successfully!');
        }

        return redirect()->route('news.index')
            ->with('success', 'Thank you! Your news/event notice has been submitted and will appear once approved by Administrator.');
    }

    /**
     * Store comment on news/event.
     */
    public function storeComment(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to leave a comment.');
        }

        $validated = $request->validate([
            'comment' => 'required|string|min:3|max:1000',
        ]);

        $item = NewsEvent::findOrFail($id);

        NewsEventComment::create([
            'news_event_id' => $item->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Comment added successfully.');
    }

    /**
     * Delete / Remove a news/event comment (Admin privilege).
     */
    public function destroyComment($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Only administrators can delete comments.');
        }

        $comment = NewsEventComment::findOrFail($id);
        $comment->delete();

        return back()->with('success', 'Comment removed successfully.');
    }
}
