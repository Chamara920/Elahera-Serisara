<?php

namespace App\Services;

use App\Models\BusStop;
use App\Models\BusStopTime;
use App\Models\Destination;

/**
 * Class TransitRoutingService
 *
 * Implements the Time-Expanded Transit Routing Engine for the Elahera / Bakamuna corridor.
 * Handles direct trips, 1-transfer connections via regional hubs (e.g. Polathuwadiya, Bakamuna),
 * alternative route evaluations (Fastest vs Shorter distance), missed-bus alerts,
 * and itinerary sequence optimization.
 */
class TransitRoutingService
{
    /**
     * Default buffer in minutes required to change buses safely at a junction.
     */
    public const DEFAULT_TRANSFER_BUFFER_MINUTES = 10;

    /**
     * Find best transit connections (Direct and Transfer options) between two bus stops
     * departing at or after a specified time.
     *
     * @param  int  $fromStopId  Departure bus stop ID
     * @param  int  $toStopId  Destination bus stop ID
     * @param  int  $departureMinutes  Departure time as minutes past midnight (0 - 1439)
     * @param  int  $minTransferBuffer  Minimum buffer in minutes between connecting buses
     * @param  string  $serviceDay  'all', 'weekdays', 'weekends', 'poya'
     * @return array List of route alternatives sorted by arrival time
     */
    public function findLegConnections(
        int $fromStopId,
        int $toStopId,
        int $departureMinutes,
        int $minTransferBuffer = self::DEFAULT_TRANSFER_BUFFER_MINUTES,
        string $serviceDay = 'all'
    ): array {
        // If same stop, return instant zero-distance transfer
        if ($fromStopId === $toStopId) {
            $stop = BusStop::find($fromStopId);

            return [[
                'type' => 'same_stop',
                'transfers_count' => 0,
                'departure_minutes' => $departureMinutes,
                'departure_time' => $this->formatMinutesToTime($departureMinutes),
                'arrival_minutes' => $departureMinutes,
                'arrival_time' => $this->formatMinutesToTime($departureMinutes),
                'total_duration_minutes' => 0,
                'waiting_minutes' => 0,
                'travel_minutes' => 0,
                'is_recommended' => true,
                'summary_badge' => 'ස්ථාන මාරුවක් නැත (Same Stop)',
                'via_hub_name' => null,
                'legs' => [],
            ]];
        }

        $options = [];

        // 1. Direct Buses (0 Transfers)
        $directOptions = $this->findDirectBuses($fromStopId, $toStopId, $departureMinutes, $serviceDay);
        foreach ($directOptions as $d) {
            $options[] = $d;
        }

        // 2. Transfer Connections (1 Transfer via Interchange Hubs)
        $transferOptions = $this->findTransferConnections($fromStopId, $toStopId, $departureMinutes, $minTransferBuffer, $serviceDay);
        foreach ($transferOptions as $t) {
            $options[] = $t;
        }

        if (empty($options)) {
            return [];
        }

        // Sort all options primarily by Earliest Arrival Time, secondarily by Total Duration
        usort($options, function ($a, $b) {
            if ($a['arrival_minutes'] === $b['arrival_minutes']) {
                return $a['total_duration_minutes'] <=> $b['total_duration_minutes'];
            }

            return $a['arrival_minutes'] <=> $b['arrival_minutes'];
        });

        // Deduplicate very similar options (same departure and arrival times)
        $uniqueOptions = [];
        $seenSignatures = [];

        foreach ($options as $opt) {
            $sig = "{$opt['departure_minutes']}_{$opt['arrival_minutes']}_{$opt['transfers_count']}";
            if (! isset($seenSignatures[$sig])) {
                $seenSignatures[$sig] = true;
                $uniqueOptions[] = $opt;
            }
        }

        // Tag the best option and distinct alternatives
        if (! empty($uniqueOptions)) {
            $uniqueOptions[0]['is_recommended'] = true;
            $uniqueOptions[0]['summary_badge'] = $uniqueOptions[0]['transfers_count'] === 0
                ? '⚡ සෘජු බස් රථය (Direct Bus)'
                : '⚡ ඉක්මන්ම මාර්ගය (Fastest Route)';

            // If an alternative exists with a different hub or different transfer count, badge it
            for ($i = 1; $i < count($uniqueOptions); $i++) {
                $uniqueOptions[$i]['is_recommended'] = false;
                if ($uniqueOptions[$i]['transfers_count'] === 0) {
                    $uniqueOptions[$i]['summary_badge'] = 'සෘජු බස් රථය (Direct Bus)';
                } else {
                    $viaName = $uniqueOptions[$i]['via_hub_name'] ?? 'විකල්ප මාර්ගය';
                    $uniqueOptions[$i]['summary_badge'] = "🔄 විකල්ප මාර්ගය ({$viaName} හරහා)";
                }
            }
        }

        return array_slice($uniqueOptions, 0, 3); // Return top 3 viable options
    }

    /**
     * Find direct buses running from $fromStopId to $toStopId.
     */
    protected function findDirectBuses(int $fromStopId, int $toStopId, int $departureMinutes, string $serviceDay): array
    {
        $results = [];

        // Get origin stop times for trips departing at or after $departureMinutes
        $originStopTimes = BusStopTime::with(['busTrip.busRoute', 'busStop'])
            ->where('bus_stop_id', $fromStopId)
            ->where('departure_minutes', '>=', $departureMinutes)
            ->whereHas('busTrip', function ($q) use ($serviceDay) {
                $q->where('is_active', true);
                if ($serviceDay !== 'all') {
                    $q->whereIn('service_days', ['all', $serviceDay]);
                }
            })
            ->orderBy('departure_minutes')
            ->limit(10)
            ->get();

        foreach ($originStopTimes as $originSt) {
            $trip = $originSt->busTrip;
            $route = $trip->busRoute;

            // Check if destination stop exists later in the same trip
            $destSt = BusStopTime::with('busStop')
                ->where('bus_trip_id', $trip->id)
                ->where('bus_stop_id', $toStopId)
                ->where('stop_sequence', '>', $originSt->stop_sequence)
                ->first();

            if ($destSt) {
                $waitMins = $originSt->departure_minutes - $departureMinutes;
                $rideMins = $destSt->arrival_minutes - $originSt->departure_minutes;
                $totalMins = $destSt->arrival_minutes - $departureMinutes;

                $results[] = [
                    'type' => 'direct',
                    'transfers_count' => 0,
                    'departure_minutes' => $originSt->departure_minutes,
                    'departure_time' => $originSt->departure_time,
                    'arrival_minutes' => $destSt->arrival_minutes,
                    'arrival_time' => $destSt->arrival_time,
                    'total_duration_minutes' => $totalMins,
                    'waiting_minutes' => $waitMins,
                    'travel_minutes' => $rideMins,
                    'via_hub_name' => null,
                    'is_recommended' => false,
                    'summary_badge' => 'සෘජු බස් රථය (Direct Bus)',
                    'legs' => [
                        [
                            'leg_number' => 1,
                            'route_number' => $route->route_number,
                            'route_name_en' => "Route {$route->route_number}: {$route->origin} - {$route->destination}",
                            'route_name_si' => "මාර්ග අංක {$route->route_number}: {$route->origin} සිට {$route->destination}",
                            'trip_name' => $trip->getLocalizedTripName(),
                            'service_type' => $route->service_type,
                            'board_stop_id' => $fromStopId,
                            'board_stop_name_en' => $originSt->busStop->name_en,
                            'board_stop_name_si' => $originSt->busStop->name_si,
                            'departure_minutes' => $originSt->departure_minutes,
                            'departure_time' => $originSt->departure_time,
                            'alight_stop_id' => $toStopId,
                            'alight_stop_name_en' => $destSt->busStop->name_en,
                            'alight_stop_name_si' => $destSt->busStop->name_si,
                            'arrival_minutes' => $destSt->arrival_minutes,
                            'arrival_time' => $destSt->arrival_time,
                            'ride_duration_minutes' => $rideMins,
                        ],
                    ],
                ];
            }
        }

        return $results;
    }

    /**
     * Find 1-transfer connections via interchange hubs (e.g. Polathuwadiya, Bakamuna, Elahera, Naula, Dambulla).
     */
    protected function findTransferConnections(int $fromStopId, int $toStopId, int $departureMinutes, int $minTransferBuffer, string $serviceDay): array
    {
        $results = [];

        // All designated interchange hubs (or stops that serve multiple routes)
        $transferHubs = BusStop::where('is_hub', true)
            ->whereNotIn('id', [$fromStopId, $toStopId])
            ->get();

        foreach ($transferHubs as $hub) {
            // Find Leg 1: From origin to this Hub
            $leg1OriginTimes = BusStopTime::with(['busTrip.busRoute', 'busStop'])
                ->where('bus_stop_id', $fromStopId)
                ->where('departure_minutes', '>=', $departureMinutes)
                ->whereHas('busTrip', function ($q) use ($serviceDay) {
                    $q->where('is_active', true);
                    if ($serviceDay !== 'all') {
                        $q->whereIn('service_days', ['all', $serviceDay]);
                    }
                })
                ->orderBy('departure_minutes')
                ->limit(6)
                ->get();

            foreach ($leg1OriginTimes as $st1) {
                $trip1 = $st1->busTrip;
                $route1 = $trip1->busRoute;

                $hubArrivalSt1 = BusStopTime::with('busStop')
                    ->where('bus_trip_id', $trip1->id)
                    ->where('bus_stop_id', $hub->id)
                    ->where('stop_sequence', '>', $st1->stop_sequence)
                    ->first();

                if (! $hubArrivalSt1) {
                    continue; // This trip does not stop at this hub
                }

                $hubArrivalMin = $hubArrivalSt1->arrival_minutes;
                $earliestLeg2DepMin = $hubArrivalMin + $minTransferBuffer;

                // Find Leg 2: From this Hub to destination
                $leg2OriginTimes = BusStopTime::with(['busTrip.busRoute', 'busStop'])
                    ->where('bus_stop_id', $hub->id)
                    ->where('departure_minutes', '>=', $earliestLeg2DepMin)
                    ->where('bus_trip_id', '!=', $trip1->id) // Must be a different trip
                    ->whereHas('busTrip', function ($q) use ($serviceDay) {
                        $q->where('is_active', true);
                        if ($serviceDay !== 'all') {
                            $q->whereIn('service_days', ['all', $serviceDay]);
                        }
                    })
                    ->orderBy('departure_minutes')
                    ->limit(4)
                    ->get();

                foreach ($leg2OriginTimes as $st2) {
                    $trip2 = $st2->busTrip;
                    $route2 = $trip2->busRoute;

                    $destSt2 = BusStopTime::with('busStop')
                        ->where('bus_trip_id', $trip2->id)
                        ->where('bus_stop_id', $toStopId)
                        ->where('stop_sequence', '>', $st2->stop_sequence)
                        ->first();

                    if (! $destSt2) {
                        continue;
                    }

                    // Calculate timing metrics
                    $initialWait = $st1->departure_minutes - $departureMinutes;
                    $ride1 = $hubArrivalMin - $st1->departure_minutes;
                    $transferWait = $st2->departure_minutes - $hubArrivalMin;
                    $ride2 = $destSt2->arrival_minutes - $st2->departure_minutes;
                    $totalDuration = $destSt2->arrival_minutes - $departureMinutes;

                    // Avoid connections where the transfer wait is absurdly long (> 150 mins)
                    if ($transferWait > 150) {
                        continue;
                    }

                    $hubName = $hub->getLocalizedName();

                    $results[] = [
                        'type' => 'transfer',
                        'transfers_count' => 1,
                        'departure_minutes' => $st1->departure_minutes,
                        'departure_time' => $st1->departure_time,
                        'arrival_minutes' => $destSt2->arrival_minutes,
                        'arrival_time' => $destSt2->arrival_time,
                        'total_duration_minutes' => $totalDuration,
                        'waiting_minutes' => $initialWait + $transferWait,
                        'travel_minutes' => $ride1 + $ride2,
                        'via_hub_name' => $hubName,
                        'via_hub_id' => $hub->id,
                        'transfer_wait_minutes' => $transferWait,
                        'is_recommended' => false,
                        'summary_badge' => "🔄 {$hubName} හරහා බස් මාරුව",
                        'legs' => [
                            [
                                'leg_number' => 1,
                                'route_number' => $route1->route_number,
                                'route_name_en' => "Route {$route1->route_number}: {$route1->origin} - {$route1->destination}",
                                'route_name_si' => "මාර්ග අංක {$route1->route_number}: {$route1->origin} සිට {$route1->destination}",
                                'trip_name' => $trip1->getLocalizedTripName(),
                                'service_type' => $route1->service_type,
                                'board_stop_id' => $fromStopId,
                                'board_stop_name_en' => $st1->busStop->name_en,
                                'board_stop_name_si' => $st1->busStop->name_si,
                                'departure_minutes' => $st1->departure_minutes,
                                'departure_time' => $st1->departure_time,
                                'alight_stop_id' => $hub->id,
                                'alight_stop_name_en' => $hub->name_en,
                                'alight_stop_name_si' => $hub->name_si,
                                'arrival_minutes' => $hubArrivalMin,
                                'arrival_time' => $hubArrivalSt1->arrival_time,
                                'ride_duration_minutes' => $ride1,
                            ],
                            [
                                'leg_number' => 2,
                                'route_number' => $route2->route_number,
                                'route_name_en' => "Route {$route2->route_number}: {$route2->origin} - {$route2->destination}",
                                'route_name_si' => "මාර්ග අංක {$route2->route_number}: {$route2->origin} සිට {$route2->destination}",
                                'trip_name' => $trip2->getLocalizedTripName(),
                                'service_type' => $route2->service_type,
                                'board_stop_id' => $hub->id,
                                'board_stop_name_en' => $hub->name_en,
                                'board_stop_name_si' => $hub->name_si,
                                'departure_minutes' => $st2->departure_minutes,
                                'departure_time' => $st2->departure_time,
                                'alight_stop_id' => $toStopId,
                                'alight_stop_name_en' => $destSt2->busStop->name_en,
                                'alight_stop_name_si' => $destSt2->busStop->name_si,
                                'arrival_minutes' => $destSt2->arrival_minutes,
                                'arrival_time' => $destSt2->arrival_time,
                                'ride_duration_minutes' => $ride2,
                                'transfer_wait_at_board_minutes' => $transferWait,
                            ],
                        ],
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * Build full day-trip itinerary with public transit bus connections.
     *
     * @param  int  $startStopId  Starting/Ending hub bus stop ID (e.g. Bakamuna Town)
     * @param  array  $stopsWithDurations  Array of ['destination_id' => int, 'stay_minutes' => int]
     * @param  int  $startMinutes  Trip start time in minutes (e.g. 480 for 08:00 AM)
     * @param  bool  $returnToStart  Whether the journey returns to $startStopId
     * @param  array  $chosenAlternativeIndices  User overrides for specific legs ['0' => 1]
     */
    public function planItinerary(
        int $startStopId,
        array $stopsWithDurations,
        int $startMinutes,
        bool $returnToStart = true,
        array $chosenAlternativeIndices = [],
        string $serviceDay = 'all'
    ): array {
        $startStop = BusStop::find($startStopId);
        if (! $startStop) {
            $startStop = BusStop::where('code', 'BAKAMUNA')->first() ?? BusStop::first();
            $startStopId = $startStop->id;
        }

        $timeline = [];
        $currentMinutes = $startMinutes;
        $currentStopId = $startStopId;
        $currentStopName = $startStop->getLocalizedName();

        $totalTravelMinutes = 0;
        $totalWaitingMinutes = 0;
        $totalStayMinutes = 0;
        $totalTransfersCount = 0;
        $hasMissedConnections = false;

        $destinationIds = array_column($stopsWithDurations, 'destination_id');
        $destinations = Destination::with('nearestBusStop')
            ->whereIn('id', $destinationIds)
            ->get()
            ->keyBy('id');

        $legsData = [];

        foreach ($stopsWithDurations as $legIndex => $stopItem) {
            $destId = $stopItem['destination_id'];
            $dest = $destinations->get($destId);
            if (! $dest) {
                continue;
            }

            $targetStop = $dest->nearestBusStop;
            if (! $targetStop) {
                // Fallback to Bakamuna if not set
                $targetStop = $startStop;
            }

            $stayDuration = (int) ($stopItem['stay_minutes'] ?? $dest->stay_duration_minutes ?? 60);

            // Find transit connections for this leg
            $connections = $this->findLegConnections($currentStopId, $targetStop->id, $currentMinutes, self::DEFAULT_TRANSFER_BUFFER_MINUTES, $serviceDay);

            // User may have picked an alternative index (0 = default recommended, 1 = alt B, etc.)
            $chosenIndex = (int) ($chosenAlternativeIndices[$legIndex] ?? 0);
            $chosenConnection = $connections[$chosenIndex] ?? ($connections[0] ?? null);

            $legRecord = [
                'leg_index' => $legIndex,
                'from_stop_id' => $currentStopId,
                'from_stop_name' => $currentStopName,
                'destination_id' => $dest->id,
                'destination_title' => $dest->getLocalizedTitle(),
                'to_stop_id' => $targetStop->id,
                'to_stop_name' => $targetStop->getLocalizedName(),
                'stay_minutes' => $stayDuration,
                'departure_search_time' => $this->formatMinutesToTime($currentMinutes),
                'available_alternatives' => $connections,
                'selected_alternative_index' => $chosenIndex,
                'connection' => $chosenConnection,
                'is_feasible' => ($chosenConnection !== null),
                'warning_message' => null,
            ];

            if ($chosenConnection) {
                $totalTravelMinutes += $chosenConnection['travel_minutes'];
                $totalWaitingMinutes += $chosenConnection['waiting_minutes'];
                $totalTransfersCount += $chosenConnection['transfers_count'];

                // Advance clock to arrival at destination
                $arrivalAtDestMinutes = $chosenConnection['arrival_minutes'];
                $legRecord['arrival_time'] = $this->formatMinutesToTime($arrivalAtDestMinutes);

                // Add stay duration
                $totalStayMinutes += $stayDuration;
                $departureFromDestMinutes = $arrivalAtDestMinutes + $stayDuration;
                $legRecord['stay_end_time'] = $this->formatMinutesToTime($departureFromDestMinutes);

                // Advance state for next leg
                $currentMinutes = $departureFromDestMinutes;
                $currentStopId = $targetStop->id;
                $currentStopName = $targetStop->getLocalizedName();
            } else {
                $hasMissedConnections = true;
                $legRecord['warning_message'] = "⚠️ අවධානයට: {$currentStopName} සිට {$targetStop->getLocalizedName()} දක්වා {$this->formatMinutesToTime($currentMinutes)} න් පසු බස් රථ සේවාවක් සොයාගත නොහැක!";
                // Estimate fallback walking/local transfer to allow itinerary completion simulation
                $currentMinutes += 60 + $stayDuration;
                $currentStopId = $targetStop->id;
                $currentStopName = $targetStop->getLocalizedName();
            }

            $legsData[] = $legRecord;
        }

        // Return leg to starting point
        $returnRecord = null;
        if ($returnToStart && $currentStopId !== $startStopId) {
            $returnConnections = $this->findLegConnections($currentStopId, $startStopId, $currentMinutes, self::DEFAULT_TRANSFER_BUFFER_MINUTES, $serviceDay);
            $chosenReturnIndex = (int) ($chosenAlternativeIndices['return'] ?? 0);
            $chosenReturn = $returnConnections[$chosenReturnIndex] ?? ($returnConnections[0] ?? null);

            $returnRecord = [
                'leg_index' => 'return',
                'from_stop_id' => $currentStopId,
                'from_stop_name' => $currentStopName,
                'to_stop_id' => $startStopId,
                'to_stop_name' => $startStop->getLocalizedName(),
                'departure_search_time' => $this->formatMinutesToTime($currentMinutes),
                'available_alternatives' => $returnConnections,
                'selected_alternative_index' => $chosenReturnIndex,
                'connection' => $chosenReturn,
                'is_feasible' => ($chosenReturn !== null),
                'warning_message' => null,
            ];

            if ($chosenReturn) {
                $totalTravelMinutes += $chosenReturn['travel_minutes'];
                $totalWaitingMinutes += $chosenReturn['waiting_minutes'];
                $totalTransfersCount += $chosenReturn['transfers_count'];
                $currentMinutes = $chosenReturn['arrival_minutes'];
                $returnRecord['arrival_time'] = $this->formatMinutesToTime($currentMinutes);
            } else {
                $hasMissedConnections = true;
                $returnRecord['warning_message'] = "⚠️ අවධානයට: නැවත {$startStop->getLocalizedName()} බලා යාමට {$this->formatMinutesToTime($currentMinutes)} න් පසු ආපසු බස් රථයක් හමු නොවීය!";
                $currentMinutes += 60;
            }
        }

        $totalUsedMinutes = ($currentMinutes - $startMinutes);

        return [
            'start_stop' => [
                'id' => $startStop->id,
                'name_en' => $startStop->name_en,
                'name_si' => $startStop->name_si,
                'code' => $startStop->code,
                'latitude' => $startStop->latitude,
                'longitude' => $startStop->longitude,
            ],
            'start_time' => $this->formatMinutesToTime($startMinutes),
            'start_minutes' => $startMinutes,
            'finish_time' => $this->formatMinutesToTime($currentMinutes),
            'finish_minutes' => $currentMinutes,
            'total_used_minutes' => $totalUsedMinutes,
            'total_travel_minutes' => $totalTravelMinutes,
            'total_waiting_minutes' => $totalWaitingMinutes,
            'total_stay_minutes' => $totalStayMinutes,
            'total_transfers_count' => $totalTransfersCount,
            'has_missed_connections' => $hasMissedConnections,
            'legs' => $legsData,
            'return_leg' => $returnRecord,
        ];
    }

    /**
     * Permutate and find the destination sequence that minimizes total waiting & travel time.
     *
     * @param  int  $startStopId  Starting hub bus stop ID
     * @param  array  $stopsWithDurations  Array of ['destination_id' => int, 'stay_minutes' => int]
     * @param  int  $startMinutes  Start time
     */
    public function optimizeDestinationSequence(
        int $startStopId,
        array $stopsWithDurations,
        int $startMinutes,
        bool $returnToStart = true,
        string $serviceDay = 'all'
    ): array {
        $n = count($stopsWithDurations);
        if ($n <= 1) {
            return [
                'optimized_stops' => $stopsWithDurations,
                'time_saved_minutes' => 0,
                'itinerary' => $this->planItinerary($startStopId, $stopsWithDurations, $startMinutes, $returnToStart, [], $serviceDay),
            ];
        }

        // Limit full permutation search to max 5 stops to ensure sub-millisecond response
        if ($n > 5) {
            $stopsWithDurations = array_slice($stopsWithDurations, 0, 5);
            $n = 5;
        }

        $allPermutations = $this->getPermutations($stopsWithDurations);
        $originalPlan = $this->planItinerary($startStopId, $stopsWithDurations, $startMinutes, $returnToStart, [], $serviceDay);
        $bestPlan = $originalPlan;
        $bestPermutation = $stopsWithDurations;
        $bestTotalUsed = $originalPlan['total_used_minutes'];

        foreach ($allPermutations as $perm) {
            $plan = $this->planItinerary($startStopId, $perm, $startMinutes, $returnToStart, [], $serviceDay);

            // Prefer plans without missed connections
            if (! $plan['has_missed_connections'] && $bestPlan['has_missed_connections']) {
                $bestPlan = $plan;
                $bestPermutation = $perm;
                $bestTotalUsed = $plan['total_used_minutes'];

                continue;
            }

            if ($plan['has_missed_connections'] && ! $bestPlan['has_missed_connections']) {
                continue;
            }

            // If both feasible, compare total used minutes & waiting time
            if ($plan['total_used_minutes'] < $bestTotalUsed) {
                $bestTotalUsed = $plan['total_used_minutes'];
                $bestPlan = $plan;
                $bestPermutation = $perm;
            }
        }

        $timeSaved = max(0, $originalPlan['total_used_minutes'] - $bestTotalUsed);

        return [
            'optimized_stops' => $bestPermutation,
            'time_saved_minutes' => $timeSaved,
            'itinerary' => $bestPlan,
            'is_reordered' => ($bestPermutation !== $stopsWithDurations),
        ];
    }

    /**
     * Compute array permutations.
     */
    protected function getPermutations(array $items): array
    {
        if (count($items) <= 1) {
            return [$items];
        }

        $result = [];
        for ($i = 0; $i < count($items); $i++) {
            $current = $items[$i];
            $remaining = array_values(array_diff_key($items, [$i => $items[$i]]));
            foreach ($this->getPermutations($remaining) as $perm) {
                $result[] = array_merge([$current], $perm);
            }
        }

        return $result;
    }

    /**
     * Format minutes past midnight into human readable 12-hour AM/PM string.
     */
    public function formatMinutesToTime(int $minutes): string
    {
        $minutes = $minutes % 1440;
        if ($minutes < 0) {
            $minutes += 1440;
        }

        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        $ampm = $h >= 12 ? 'PM' : 'AM';
        $h12 = $h % 12;
        if ($h12 === 0) {
            $h12 = 12;
        }

        return sprintf('%02d:%02d %s', $h12, $m, $ampm);
    }

    /**
     * Parse 12-hour AM/PM or 24-hour HH:MM into minutes from midnight.
     */
    public function parseTimeToMinutes(string $timeStr): int
    {
        $timeStr = trim($timeStr);
        $parts = preg_split('/\s+/', $timeStr);
        $hParts = explode(':', $parts[0]);
        $hours = (int) ($hParts[0] ?? 0);
        $minutes = (int) ($hParts[1] ?? 0);
        $ampm = strtoupper($parts[1] ?? '');

        if ($ampm === 'PM' && $hours < 12) {
            $hours += 12;
        } elseif ($ampm === 'AM' && $hours === 12) {
            $hours = 0;
        }

        return $hours * 60 + $minutes;
    }
}
