<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\SavedTrip;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserController
 *
 * Handles member dashboard, saved itineraries, and user-submitted blog posts.
 */
class UserController extends Controller
{
    /**
     * View user's saved trips.
     */
    public function savedTrips()
    {
        $trips = SavedTrip::where('user_id', Auth::id())
            ->latest()
            ->paginate(8);

        return view('user.saved-trips', compact('trips'));
    }

    /**
     * Delete a saved trip.
     */
    public function deleteTrip($id)
    {
        $trip = SavedTrip::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $trip->delete();

        return back()->with('success', 'Trip plan removed from your account.');
    }

    /**
     * View user's community blog posts with moderation status.
     */
    public function myBlogPosts()
    {
        $posts = BlogPost::where('user_id', Auth::id())
            ->latest()
            ->paginate(8);

        return view('user.my-blogs', compact('posts'));
    }
}
