<?php

namespace App\Http\Controllers;

use App\Models\BusRoute;
use App\Models\BusRouteHalt;
use App\Models\Destination;
use Illuminate\Http\Request;

/**
 * Class TransportController
 *
 * Public transport bus search engine, Bakamuna hub master timetable, and stop filter.
 */
class TransportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Fetch all unique halt names for dropdowns
        $haltsList = BusRouteHalt::distinct()->pluck('stop_name')->sort()->values();

        // Also fetch active destinations
        $destinations = Destination::where('is_active', true)->orderBy('title_en')->get();

        // 2. Query for Route Finder (From -> To)
        $fromStop = $request->input('from');
        $toStop = $request->input('to');
        $foundRoutes = collect();

        if ($fromStop && $toStop) {
            // Find routes that stop at both From and To, with From before To
            $routeIds = BusRouteHalt::where('stop_name', 'like', "%{$fromStop}%")
                ->pluck('bus_route_id');

            $matchedRoutes = BusRoute::with('halts.destination')
                ->where('is_active', true)
                ->whereIn('id', $routeIds)
                ->get()
                ->filter(function ($route) use ($fromStop, $toStop) {
                    $halts = $route->halts;
                    $fromHalt = $halts->first(fn ($h) => stripos($h->stop_name, $fromStop) !== false);
                    $toHalt = $halts->first(fn ($h) => stripos($h->stop_name, $toStop) !== false);

                    return $fromHalt && $toHalt && $fromHalt->stop_order <= $toHalt->stop_order;
                });

            $foundRoutes = $matchedRoutes;
        }

        // 3. Stop / Destination Filter:
        // Filter bus halts at a specific selected stop or destination
        $selectedStop = $request->input('filter_stop');
        $stopTimetable = collect();

        if ($selectedStop) {
            $stopTimetable = BusRouteHalt::with('busRoute', 'destination')
                ->whereHas('busRoute', fn ($q) => $q->where('is_active', true))
                ->where(function ($q) use ($selectedStop) {
                    $q->where('stop_name', 'like', "%{$selectedStop}%")
                        ->orWhere('nearest_spot_name', 'like', "%{$selectedStop}%")
                        ->orWhereHas('destination', fn ($dq) => $dq->where('title_en', 'like', "%{$selectedStop}%")->orWhere('title_si', 'like', "%{$selectedStop}%"));
                })
                ->get()
                ->sortBy(function ($halt) {
                    // Sort by departure or arrival time
                    $time = $halt->departure_time ?: $halt->arrival_time ?: '12:00 PM';

                    return strtotime($time);
                });
        }

        // 4. Bakamuna Hub Master Timetable
        // All active routes ordered chronologically
        $allRoutes = BusRoute::with('halts.destination')
            ->where('is_active', true)
            ->get()
            ->sortBy(function ($route) {
                return strtotime($route->origin_departure_time);
            });

        return view('transport.index', compact(
            'haltsList',
            'destinations',
            'fromStop',
            'toStop',
            'foundRoutes',
            'selectedStop',
            'stopTimetable',
            'allRoutes'
        ));
    }
}
