<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\DestinationCategory;
use App\Models\DestinationReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DestinationController
 *
 * Handles destination directory, search/filtering, detail view, and review submission.
 */
class DestinationController extends Controller
{
    /**
     * Display destinations listing with top slider, filters, and 9-item pagination.
     */
    public function index(Request $request)
    {
        // 1. Top Slider: Featured 3-5 destinations controlled via admin
        $sliderDestinations = Destination::where('is_active', true)
            ->where('is_featured', true)
            ->orderByDesc('featured_priority')
            ->take(5)
            ->get();

        // 2. Query builder for filtering
        $query = Destination::with(['categories'])->where('is_active', true);

        // Search by keyword
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_si', 'like', "%{$search}%")
                    ->orWhere('short_description_en', 'like', "%{$search}%")
                    ->orWhere('description_en', 'like', "%{$search}%");
            });
        }

        // Filter by category (matches primary or any associated category)
        if ($request->filled('category') && $request->category !== 'all') {
            $catFilter = $request->category;
            $query->where(function ($q) use ($catFilter) {
                $q->where('category', $catFilter)
                    ->orWhereHas('categories', function ($catQ) use ($catFilter) {
                        $catQ->where('name_en', $catFilter)
                            ->orWhere('slug', $catFilter);
                    });
            });
        }

        // Filter by route zone
        if ($request->filled('zone') && $request->zone !== 'all') {
            $query->where('route_zone', $request->zone);
        }

        // 3. Sorting & Pagination (9 destinations per page: 3 columns x 3 rows on desktop)
        $destinations = $query->orderByDesc('featured_priority')
            ->orderBy('title_en')
            ->paginate(9)
            ->withQueryString();

        // Available categories and zones for dropdown filters
        $categories = DestinationCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->pluck('name_en')
            ->filter();
        if ($categories->isEmpty()) {
            $categories = Destination::where('is_active', true)->distinct()->pluck('category')->filter();
        }
        $zones = Destination::where('is_active', true)->distinct()->pluck('route_zone')->filter();

        return view('destinations.index', compact('sliderDestinations', 'destinations', 'categories', 'zones'));
    }

    /**
     * Single Destination Detail View.
     */
    public function show($slug)
    {
        $destination = Destination::with(['categories', 'nearestBusStop'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment views count
        $destination->increment('views_count');

        // Nearby recommended destinations (2-3 cards in same zone or category)
        $nearbyDestinations = Destination::with('categories')
            ->where('is_active', true)
            ->where('id', '!=', $destination->id)
            ->where(function ($q) use ($destination) {
                $q->where('route_zone', $destination->route_zone)
                    ->orWhere('category', $destination->category)
                    ->orWhereHas('categories', function ($catQ) use ($destination) {
                        $catQ->where('destination_categories.id', $destination->category_id);
                    });
            })
            ->take(3)
            ->get();

        // Approved reviews with user relationships
        $reviews = $destination->reviews()
            ->with('user')
            ->where('is_approved', true)
            ->latest()
            ->get();

        return view('destinations.show', compact('destination', 'nearbyDestinations', 'reviews'));
    }

    /**
     * Store review and rating submitted by registered community members.
     */
    public function storeReview(Request $request, $id)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to submit a review and rating.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ]);

        $destination = Destination::findOrFail($id);

        DestinationReview::create([
            'destination_id' => $destination->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => true, // Auto-approve community reviews
        ]);

        return back()->with('success', 'Thank you! Your review has been posted.');
    }

    /**
     * Delete / Remove a user review and rating (Admin privilege).
     */
    public function destroyReview($id)
    {
        if (! Auth::check() || ! Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Only administrators can delete reviews.');
        }

        $review = DestinationReview::findOrFail($id);
        $review->delete();

        return back()->with('success', 'User comment and rating removed successfully.');
    }
}
