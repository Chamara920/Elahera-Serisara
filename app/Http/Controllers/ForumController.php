<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumReply;
use App\Models\ForumTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class ForumController
 *
 * Community discussion forum. Direct publication without admin approval.
 */
class ForumController extends Controller
{
    /**
     * Forum listing and categories.
     */
    public function index(Request $request)
    {
        $categorySlug = $request->input('category');
        $search = $request->input('search');

        $query = ForumTopic::with(['user', 'category', 'replies']);

        if ($categorySlug) {
            $category = ForumCategory::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('forum_category_id', $category->id);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $topics = $query->latest()->paginate(10)->withQueryString();
        $categories = ForumCategory::withCount('topics')->get();

        return view('forum.index', compact('topics', 'categories', 'categorySlug', 'search'));
    }

    /**
     * Show single discussion topic and replies.
     */
    public function show($slug)
    {
        $topic = ForumTopic::with(['user', 'category', 'replies.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        $topic->increment('views_count');

        return view('forum.show', compact('topic'));
    }

    /**
     * Show create topic form.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to start a forum discussion.');
        }

        $categories = ForumCategory::all();
        return view('forum.create', compact('categories'));
    }

    /**
     * Store new forum topic (Direct publication - no approval needed).
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to post in the forum.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'forum_category_id' => 'required|exists:forum_categories,id',
            'content' => 'required|string|min:20',
        ]);

        $topic = ForumTopic::create([
            'user_id' => Auth::id(),
            'forum_category_id' => $validated['forum_category_id'],
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
            'content' => $validated['content'],
        ]);

        return redirect()->route('forum.show', $topic->slug)
            ->with('success', 'Your question / topic has been posted successfully!');
    }

    /**
     * Store reply to a forum topic.
     */
    public function storeReply(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to reply.');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:5|max:2000',
        ]);

        $topic = ForumTopic::findOrFail($id);

        ForumReply::create([
            'forum_topic_id' => $topic->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Your reply has been posted.');
    }
}
