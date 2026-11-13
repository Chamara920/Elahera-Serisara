<?php

namespace App\Http\Controllers;

use App\Models\BusStop;
use App\Models\Destination;
use App\Models\SavedTrip;
use App\Services\TransitRoutingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class TripPlannerController
 *
 * Implements the interactive Smart Day-Trip Planner, itinerary builder,
 * sticky time-meter calculations, Leaflet map route, Google Maps export,
 * public transit routing engine with transfer alternatives, and trip saving.
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
        $destinations = Destination::with('nearestBusStop')
            ->where('is_active', true)
            ->orderBy('title_en')
            ->get();

        $categories = Destination::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->filter();

        // Master bus stops for public transit routing & start hub selection
        $busStops = BusStop::orderByDesc('is_hub')->orderBy('name_en')->get();
        $defaultBusStop = $busStops->firstWhere('code', 'BAKAMUNA') ?? $busStops->first();

        return view('planner.index', compact(
            'destinations',
            'categories',
            'tripDate',
            'startLocation',
            'startTime',
            'endTime',
            'transportMode',
            'mealBreak',
            'busStops',
            'defaultBusStop'
        ));
    }

    /**
     * Calculate public transit timetable connections and alternatives for itinerary.
     */
    public function calculateTransit(Request $request, TransitRoutingService $transitService): JsonResponse
    {
        $validated = $request->validate([
            'start_stop_id' => 'required|integer|exists:bus_stops,id',
            'stops' => 'required|array|min:1',
            'stops.*.destination_id' => 'required|integer|exists:destinations,id',
            'stops.*.stay_minutes' => 'nullable|integer|min:15|max:480',
            'start_time' => 'required|string',
            'service_day' => 'nullable|string|in:all,weekdays,weekends,poya',
            'return_to_start' => 'nullable|boolean',
            'chosen_alternatives' => 'nullable|array',
        ]);

        $startMinutes = $transitService->parseTimeToMinutes($validated['start_time']);
        $serviceDay = $validated['service_day'] ?? 'all';
        $returnToStart = $request->boolean('return_to_start', true);
        $chosenAlternatives = $validated['chosen_alternatives'] ?? [];

        $plan = $transitService->planItinerary(
            (int) $validated['start_stop_id'],
            $validated['stops'],
            $startMinutes,
            $returnToStart,
            $chosenAlternatives,
            $serviceDay
        );

        return response()->json([
            'success' => true,
            'plan' => $plan,
        ]);
    }

    /**
     * Optimize destination visit order to minimize bus waiting and total travel time.
     */
    public function optimizeTransitSequence(Request $request, TransitRoutingService $transitService): JsonResponse
    {
        $validated = $request->validate([
            'start_stop_id' => 'required|integer|exists:bus_stops,id',
            'stops' => 'required|array|min:2',
            'stops.*.destination_id' => 'required|integer|exists:destinations,id',
            'stops.*.stay_minutes' => 'nullable|integer|min:15|max:480',
            'start_time' => 'required|string',
            'service_day' => 'nullable|string|in:all,weekdays,weekends,poya',
            'return_to_start' => 'nullable|boolean',
        ]);

        $startMinutes = $transitService->parseTimeToMinutes($validated['start_time']);
        $serviceDay = $validated['service_day'] ?? 'all';
        $returnToStart = $request->boolean('return_to_start', true);

        $optimization = $transitService->optimizeDestinationSequence(
            (int) $validated['start_stop_id'],
            $validated['stops'],
            $startMinutes,
            $returnToStart,
            $serviceDay
        );

        return response()->json([
            'success' => true,
            'optimization' => $optimization,
        ]);
    }

    /**
     * Return JSON list of bus stops with coordinates for the interactive planner mini map.
     */
    public function getBusStops(): JsonResponse
    {
        $stops = BusStop::orderByDesc('is_hub')
            ->orderBy('name_en')
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'name_en' => $s->name_en,
                'name_si' => $s->name_si,
                'localized_name' => $s->getLocalizedName(),
                'code' => $s->code,
                'latitude' => $s->latitude,
                'longitude' => $s->longitude,
                'is_hub' => $s->is_hub,
                'description' => $s->getLocalizedDescription(),
            ]);

        return response()->json([
            'success' => true,
            'stops' => $stops,
        ]);
    }

    /**
     * Save trip to authenticated user account.
     */
    public function saveTrip(Request $request)
    {
        if (! Auth::check()) {
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
