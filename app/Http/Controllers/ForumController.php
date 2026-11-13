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
        $langFilter = $request->input('lang');
        $currentLocale = app()->getLocale();

        $query = ForumTopic::with(['user', 'category', 'replies']);

        if ($langFilter && in_array($langFilter, ['all', 'en', 'si'])) {
            if ($langFilter !== 'all') {
                $query->where(function ($q) use ($langFilter) {
                    $q->where('language', $langFilter)->orWhere('language', 'all');
                });
            }
        } else {
            // Check if topics exist for user's selected language
            $hasMatchingLocale = ForumTopic::where(function ($q) use ($currentLocale) {
                $q->where('language', $currentLocale)->orWhere('language', 'all');
            })->exists();

            if ($hasMatchingLocale) {
                $query->where(function ($q) use ($currentLocale) {
                    $q->where('language', $currentLocale)
                        ->orWhere('language', 'all')
                        ->orWhereNull('language');
                });
            }
        }

        if ($categorySlug) {
            $category = ForumCategory::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('forum_category_id', $category->id);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_si', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('content_si', 'like', "%{$search}%");
            });
        }

        $topics = $query->latest()->paginate(10)->withQueryString();
        $categories = ForumCategory::withCount('topics')->get();

        return view('forum.index', compact('topics', 'categories', 'categorySlug', 'search', 'langFilter'));
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
        if (! Auth::check()) {
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
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to post in the forum.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_si' => 'nullable|string|max:255',
            'forum_category_id' => 'required|exists:forum_categories,id',
            'language' => 'nullable|in:all,en,si',
            'content' => 'required|string|min:20',
            'content_si' => 'nullable|string',
        ]);

        $topic = ForumTopic::create([
            'user_id' => Auth::id(),
            'forum_category_id' => $validated['forum_category_id'],
            'language' => $validated['language'] ?? 'all',
            'title' => $validated['title'],
            'title_si' => $validated['title_si'] ?? null,
            'slug' => Str::slug($validated['title']).'-'.Str::random(5),
            'content' => $validated['content'],
            'content_si' => $validated['content_si'] ?? null,
        ]);

        return redirect()->route('forum.show', $topic->slug)
            ->with('success', 'Your question / topic has been posted successfully!');
    }

    /**
     * Store reply to a forum topic.
     */
    public function storeReply(Request $request, $id)
    {
        if (! Auth::check()) {
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
