<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class BlogController
 *
 * Handles Community Blog. Submissions by community members require Admin approval.
 */
class BlogController extends Controller
{
    /**
     * Blog index with sorting (Latest, Most Viewed, Most Commented) and category filter.
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'latest');
        $categorySlug = $request->input('category');
        $langFilter = $request->input('lang');
        $currentLocale = app()->getLocale();

        $query = BlogPost::with('user', 'category')
            ->where('is_approved', true);

        if ($langFilter && in_array($langFilter, ['all', 'en', 'si'])) {
            if ($langFilter !== 'all') {
                $query->where(function ($q) use ($langFilter) {
                    $q->where('language', $langFilter)->orWhere('language', 'all');
                });
            }
        } else {
            // Check if posts exist for user's selected language
            $hasMatchingLocale = BlogPost::where('is_approved', true)
                ->where(function ($q) use ($currentLocale) {
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
            $category = BlogCategory::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        switch ($sort) {
            case 'views':
                $query->orderByDesc('views_count');
                break;
            case 'comments':
                $query->withCount('comments')->orderByDesc('comments_count');
                break;
            default:
                $query->latest();
                break;
        }

        $posts = $query->paginate(6)->withQueryString();
        $categories = BlogCategory::withCount(['posts' => fn ($q) => $q->where('is_approved', true)])->get();

        // Top contributors
        $contributors = User::whereHas('blogPosts', fn ($q) => $q->where('is_approved', true))
            ->withCount(['blogPosts' => fn ($q) => $q->where('is_approved', true)])
            ->orderByDesc('blog_posts_count')
            ->take(5)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'contributors', 'sort', 'categorySlug', 'langFilter'));
    }

    /**
     * Show single blog post.
     */
    public function show($slug)
    {
        $post = BlogPost::with(['user', 'category', 'comments.user'])
            ->where('slug', $slug)
            ->where(function ($q) {
                // If admin or post author, they can preview even if pending
                if (Auth::check() && (Auth::user()->isAdmin() || Auth::id() === BlogPost::where('slug', request()->route('slug'))->value('user_id'))) {
                    // allow
                } else {
                    $q->where('is_approved', true);
                }
            })
            ->firstOrFail();

        $post->increment('views_count');

        $relatedPosts = BlogPost::where('is_approved', true)
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    /**
     * Form to create a new blog post (auth required).
     */
    public function create()
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to write a blog post.');
        }

        $categories = BlogCategory::all();

        return view('blog.create', compact('categories'));
    }

    /**
     * Store new blog post (Submitted as pending approval).
     */
    public function store(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to write a blog post.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_si' => 'nullable|string|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'language' => 'nullable|in:all,en,si',
            'content' => 'required|string|min:50',
            'content_si' => 'nullable|string',
            'featured_image' => 'nullable|image|max:3072',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog', 'public');
        }

        // If user is admin, auto-approve; otherwise, set is_approved = false (requires admin approval)
        $isApproved = Auth::user()->isAdmin();

        $post = BlogPost::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'language' => $validated['language'] ?? 'all',
            'title' => $validated['title'],
            'title_si' => $validated['title_si'] ?? null,
            'slug' => Str::slug($validated['title']).'-'.Str::random(5),
            'content' => $validated['content'],
            'content_si' => $validated['content_si'] ?? null,
            'featured_image' => $imagePath,
            'is_approved' => $isApproved,
        ]);

        if ($isApproved) {
            return redirect()->route('blog.show', $post->slug)
                ->with('success', 'Blog article published successfully!');
        }

        return redirect()->route('user.my-blogs')
            ->with('success', 'Your blog article has been submitted for review! It will be published after Administrator approval.');
    }

    /**
     * Store a comment on a blog post.
     */
    public function storeComment(Request $request, $id)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to comment.');
        }

        $validated = $request->validate([
            'comment' => 'required|string|min:3|max:1000',
        ]);

        $post = BlogPost::findOrFail($id);

        BlogComment::create([
            'blog_post_id' => $post->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Your comment has been added.');
    }

    /**
     * Delete / Remove a blog comment (Admin privilege).
     */
    public function destroyComment($id)
    {
        if (! Auth::check() || ! Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Only administrators can delete comments.');
        }

        $comment = BlogComment::findOrFail($id);
        $comment->delete();

        return back()->with('success', 'Comment removed successfully.');
    }
}
