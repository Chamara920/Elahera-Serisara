<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\SavedTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class TripPlannerController
 *
 * Implements the interactive Smart Day-Trip Planner, itinerary builder,
 * sticky time-meter calculations, Leaflet map route, Google Maps export, and trip saving.
 */
class TripPlannerController extends Controller
{
    /**
     * Display the Smart Trip Planner.
     */
    public function index(Request $request)
    {
        // Pre-filled configuration from Homepage Quick Planner widget or query parameters
        $tripDate = $request->input('date', date('Y-m-d'));
        $startLocation = $request->input('start_location', 'Bakamuna Town');
        $startTime = $request->input('start_time', '08:00');
        $endTime = $request->input('end_time', '18:00');
        $transportMode = $request->input('transport_mode', 'Car/Van');
        $mealBreak = $request->boolean('meal_break', true);

        // Fetch all active destinations with metrics and guides
        $destinations = Destination::where('is_active', true)
            ->orderBy('title_en')
            ->get();

        $categories = Destination::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('planner.index', compact(
            'destinations',
            'categories',
            'tripDate',
            'startLocation',
            'startTime',
            'endTime',
            'transportMode',
            'mealBreak'
        ));
    }

    /**
     * Save trip to authenticated user account.
     */
    public function saveTrip(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in to save your trip itinerary to your account.',
                'redirect' => route('login'),
            ], 401);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'trip_date' => 'nullable|date',
            'start_location' => 'required|string',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'transport_mode' => 'required|string',
            'meal_break' => 'required|boolean',
            'stops' => 'required|array|min:1',
            'total_duration_minutes' => 'required|integer',
            'google_maps_url' => 'nullable|string',
        ]);

        $trip = SavedTrip::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'trip_date' => $validated['trip_date'] ?? null,
            'start_location' => $validated['start_location'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'transport_mode' => $validated['transport_mode'],
            'meal_break' => $validated['meal_break'],
            'stops_data' => $validated['stops'],
            'total_duration_minutes' => $validated['total_duration_minutes'],
            'google_maps_url' => $validated['google_maps_url'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Trip saved successfully to your account!',
            'trip_id' => $trip->id,
            'view_url' => route('user.saved-trips'),
        ]);
    }

    /**
     * Printable view of planned trip (supports PDF saving via browser print).
     */
    public function printView(Request $request)
    {
        $stopsJson = $request->input('stops', '[]');
        $stops = json_decode($stopsJson, true) ?: [];
        
        $destinationIds = array_column($stops, 'id');
        $destinations = Destination::whereIn('id', $destinationIds)->get()->keyBy('id');

        $tripDate = $request->input('date', date('Y-m-d'));
        $startLocation = $request->input('start_location', 'Bakamuna');
        $startTime = $request->input('start_time', '08:00');
        $transportMode = $request->input('transport_mode', 'Car/Van');

        return view('planner.print', compact(
            'stops',
            'destinations',
            'tripDate',
            'startLocation',
            'startTime',
            'transportMode'
        ));
    }
}
