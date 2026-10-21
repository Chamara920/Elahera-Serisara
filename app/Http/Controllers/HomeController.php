<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\HeroSlider;
use App\Models\NewsEvent;
use Illuminate\Http\Request;

/**
 * Class HomeController
 *
 * Handles the main landing page of Elahera Sirisara.
 */
class HomeController extends Controller
{
    /**
     * Render the homepage.
     */
    public function index()
    {
        // 1. Hero Sliders (Admin controlled)
        $sliders = HeroSlider::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // 2. Featured Destinations (6 cards for the 2/3 main area)
        $featuredDestinations = Destination::where('is_active', true)
            ->where('is_featured', true)
            ->orderByDesc('featured_priority')
            ->take(6)
            ->get();

        // If fewer than 6 featured, fetch regular active ones
        if ($featuredDestinations->count() < 6) {
            $more = Destination::where('is_active', true)
                ->whereNotIn('id', $featuredDestinations->pluck('id'))
                ->take(6 - $featuredDestinations->count())
                ->get();
            $featuredDestinations = $featuredDestinations->concat($more);
        }

        // 3. Latest Approved Blog Posts (for 1/3 sidebar)
        $latestBlogs = BlogPost::with('user', 'category')
            ->where('is_approved', true)
            ->latest()
            ->take(3)
            ->get();

        // 4. Latest Approved News & Events (for 1/3 sidebar)
        $latestNews = NewsEvent::where('is_approved', true)
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('sliders', 'featuredDestinations', 'latestBlogs', 'latestNews'));
    }

    /**
     * Global Search Box for destinations, blogs, and news.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        
        if (empty($query)) {
            return redirect()->route('home');
        }

        $destinations = Destination::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title_en', 'like', "%{$query}%")
                  ->orWhere('title_si', 'like', "%{$query}%")
                  ->orWhere('description_en', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%");
            })
            ->get();

        $blogs = BlogPost::where('is_approved', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->get();

        $news = NewsEvent::where('is_approved', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->get();

        return view('search', compact('query', 'destinations', 'blogs', 'news'));
    }

    /**
     * Switch application language (en / si).
     */
    public function switchLocale($locale)
    {
        if (in_array($locale, ['en', 'si'])) {
            session(['locale' => $locale]);
        }
        return back();
    }
}
