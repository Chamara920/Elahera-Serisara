<?php

namespace Database\Seeders;

use App\Models\BusRoute;
use App\Models\BusRouteHalt;
use App\Models\BusStop;
use App\Models\BusStopTime;
use App\Models\BusTrip;
use App\Models\Destination;
use Illuminate\Database\Seeder;

/**
 * Class SmartTransitSeeder
 *
 * Seeds master bus stops, the 5 regional bus routes, daily trips/turns,
 * GTFS-compatible stop timings, and connects destinations to transit halts.
 */
class SmartTransitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ---------------------------------------------------------------------
        // 1. Bus Stops Master Table
        // ---------------------------------------------------------------------
        $stopsData = [
            [
                'code' => 'BAKAMUNA',
                'name_en' => 'Bakamuna Town Hub',
                'name_si' => 'බකමූණ නගර මධ්‍යස්ථානය',
                'latitude' => 7.9300,
                'longitude' => 80.8200,
                'is_hub' => true,
                'location_description_en' => 'Main regional bus terminal, commercial hub, and transit connection point.',
                'location_description_si' => 'ප්‍රධාන බස් නැවතුම්පොළ සහ කලාපීය ප්‍රවාහන සන්ධිස්ථානය.',
            ],
            [
                'code' => 'POLATHUWADIYA',
                'name_en' => 'Polathuwadiya Junction',
                'name_si' => 'පොළත්වාඩිය හන්දිය',
                'latitude' => 7.8420,
                'longitude' => 80.7850,
                'is_hub' => true,
                'location_description_en' => 'Crucial rural transfer junction between Matale/Wilgamuwa and Mahiyanganaya/Dambulla routes.',
                'location_description_si' => 'මාතලේ-විල්ගමුව සහ මහියංගනය-දඹුල්ල මාර්ග මාරුවන ප්‍රධාන හන්දිය.',
            ],
            [
                'code' => 'ELAHERA_JNC',
                'name_en' => 'Elahera Junction',
                'name_si' => 'ඇලහැර හන්දිය',
                'latitude' => 7.7470,
                'longitude' => 80.7780,
                'is_hub' => true,
                'location_description_en' => 'Historic canal junction connecting Naula, Bakamuna, and Moragahakanda roads.',
                'location_description_si' => 'නාවුල, බකමූණ සහ මොරගහකන්ද මාර්ග එක්වන ඇලහැර මංසන්ධිය.',
            ],
            [
                'code' => 'ELAHERA_ANICUT',
                'name_en' => 'Elahera Anicut Halt',
                'name_si' => 'ඇලහැර අමුණ නැවතුම',
                'latitude' => 7.7490,
                'longitude' => 80.7800,
                'is_hub' => false,
                'location_description_en' => 'Access point for King Aggabodhi ancient stone diversion weir and viewpoint.',
                'location_description_si' => 'පුරාණ ඇලහැර අමුණ සහ වාරි තාක්ෂණ නටඹුන් පිවිසුම.',
            ],
            [
                'code' => 'MORAGAHAKANDA',
                'name_en' => 'Moragahakanda Reservoir Stop',
                'name_si' => 'මොරගහකන්ද ජලාශය නැවතුම',
                'latitude' => 7.7020,
                'longitude' => 80.7650,
                'is_hub' => false,
                'location_description_en' => 'Dam viewing gallery, spillway bridge, and reservoir boat safari departure.',
                'location_description_si' => 'මොරගහකන්ද වේල්ල, නැරඹුම් මැදිරිය සහ ජලාශ පිවිසුම.',
            ],
            [
                'code' => 'NAULA',
                'name_en' => 'Naula Town Junction',
                'name_si' => 'නාවුල නගර හන්දිය',
                'latitude' => 7.6980,
                'longitude' => 80.6500,
                'is_hub' => true,
                'location_description_en' => 'A9 highway junction connecting Kandy/Matale with Elahera canal highway.',
                'location_description_si' => 'ඒ-9 ප්‍රධාන මාර්ගය සහ ඇලහැර මාර්ගය හමුවන නාවුල මංසන්ධිය.',
            ],
            [
                'code' => 'MATALE',
                'name_en' => 'Matale Main Bus Stand',
                'name_si' => 'මාතලේ ප්‍රධාන බස් නැවතුම්පොළ',
                'latitude' => 7.4675,
                'longitude' => 80.6234,
                'is_hub' => true,
                'location_description_en' => 'District capital central terminal connecting Central Province.',
                'location_description_si' => 'මාතලේ නගර ප්‍රධාන බස් නැවතුම්පොළ.',
            ],
            [
                'code' => 'KOTTAPITIYA',
                'name_en' => 'Kottapitiya Junction',
                'name_si' => 'කොට්ටපිටිය හන්දිය',
                'latitude' => 7.9050,
                'longitude' => 80.8500,
                'is_hub' => false,
                'location_description_en' => 'Junction branching off towards Kumara Ella ruins and forest reserves.',
                'location_description_si' => 'කුමාර ඇල්ල සහ වනාන්තර සංචාරක කලාපය දෙසට හැරෙන හන්දිය.',
            ],
            [
                'code' => 'KUMARA_ELLA',
                'name_en' => 'Kumara Ella Halt',
                'name_si' => 'කුමාර ඇල්ල නැවතුම',
                'latitude' => 7.8200,
                'longitude' => 80.8800,
                'is_hub' => false,
                'location_description_en' => 'Forest stream halt adjacent to historic ruins and natural bathing pools.',
                'location_description_si' => 'කුමාර ඇල්ල දිය ඇල්ල සහ ඓතිහාසික නටඹුන් පිවිසුම් නැවතුම.',
            ],
            [
                'code' => 'WILGAMUWA',
                'name_en' => 'Wilgamuwa Bus Stop',
                'name_si' => 'විල්ගමුව බස් නැවතුම',
                'latitude' => 7.6200,
                'longitude' => 80.9300,
                'is_hub' => false,
                'location_description_en' => 'Gateway to Wasgamuwa National Park and Mahaweli river valleys.',
                'location_description_si' => 'වස්ගමුව වනෝද්‍යාන පිවිසුම සහ මහවැලි ගං ඉවුර.',
            ],
            [
                'code' => 'MAHIYANGANAYA',
                'name_en' => 'Mahiyanganaya Bus Stand',
                'name_si' => 'මහියංගනය බස් නැවතුම්පොළ',
                'latitude' => 7.3200,
                'longitude' => 80.9900,
                'is_hub' => true,
                'location_description_en' => 'Major cultural and transit hub in Uva/Central boundary.',
                'location_description_si' => 'මහියංගනය නගර බස් නැවතුම්පොළ.',
            ],
            [
                'code' => 'DAMBULLA',
                'name_en' => 'Dambulla Clock Tower Terminal',
                'name_si' => 'දඹුල්ල ඔරලෝසු කණුව බස් නැවතුම',
                'latitude' => 7.8731,
                'longitude' => 80.6517,
                'is_hub' => true,
                'location_description_en' => 'Central crossroads of Cultural Triangle and Dambulla Cave Temple.',
                'location_description_si' => 'දඹුල්ල ලෙන් විහාරය සහ සංස්කෘතික ත්‍රිකෝණයේ ප්‍රධාන මංසන්ධිය.',
            ],
            [
                'code' => 'KADURUWELA',
                'name_en' => 'Kaduruwela (Polonnaruwa) Bus Stand',
                'name_si' => 'කඳුරුවෙල (පොළොන්නරුව) බස් නැවතුම්පොළ',
                'latitude' => 7.9403,
                'longitude' => 81.0188,
                'is_hub' => true,
                'location_description_en' => 'Main railway and bus terminal for Polonnaruwa district.',
                'location_description_si' => 'පොළොන්නරුව දිස්ත්‍රික් ප්‍රධාන ප්‍රවාහන මධ්‍යස්ථානය.',
            ],
        ];

        $stopModels = [];
        foreach ($stopsData as $s) {
            $stopModels[$s['code']] = BusStop::updateOrCreate(
                ['code' => $s['code']],
                $s
            );
        }

        // ---------------------------------------------------------------------
        // 2. The 5 Main Bus Routes Master
        // ---------------------------------------------------------------------
        $routesData = [
            [
                'route_number' => '422',
                'origin' => 'Kaduruwela / Polonnaruwa',
                'destination' => 'Matale',
                'service_type' => 'Normal',
                'origin_departure_time' => '06:00 AM',
                'via_cities' => 'Bakamuna, Elahera, Moragahakanda, Naula',
                'remarks' => 'Primary lifeline corridor connecting Polonnaruwa with Matale through Moragahakanda.',
                'is_active' => true,
            ],
            [
                'route_number' => '45',
                'origin' => 'Matale',
                'destination' => 'Wilgamuwa',
                'service_type' => 'Normal',
                'origin_departure_time' => '07:00 AM',
                'via_cities' => 'Naula, Elahera, Polathuwadiya Junction, Hettipola',
                'remarks' => 'Scenic eastern canal route connecting to Polathuwadiya transfer hub.',
                'is_active' => true,
            ],
            [
                'route_number' => '35',
                'origin' => 'Mahiyanganaya',
                'destination' => 'Dambulla',
                'service_type' => 'Normal',
                'origin_departure_time' => '06:45 AM',
                'via_cities' => 'Wilgamuwa, Polathuwadiya Junction, Kumara Ella, Dambulla',
                'remarks' => 'East-West connector intersecting at Polathuwadiya and Kumara Ella.',
                'is_active' => true,
            ],
            [
                'route_number' => '550',
                'origin' => 'Kaduruwela / Polonnaruwa',
                'destination' => 'Elahera Anicut',
                'service_type' => 'Normal',
                'origin_departure_time' => '06:15 AM',
                'via_cities' => 'Bakamuna Town, Kottapitiya',
                'remarks' => 'Irrigation colony feeder route terminating at King Aggabodhi Elahera Anicut.',
                'is_active' => true,
            ],
            [
                'route_number' => '422/2',
                'origin' => 'Bakamuna Town Hub',
                'destination' => 'Dambulla',
                'service_type' => 'Express',
                'origin_departure_time' => '07:15 AM',
                'via_cities' => 'Kottapitiya, Kumara Ella, Dambulla Clock Tower',
                'remarks' => 'Direct regional connection linking Bakamuna hub to Kumara Ella and Dambulla.',
                'is_active' => true,
            ],
        ];

        $routeModels = [];
        foreach ($routesData as $r) {
            $routeModels[$r['route_number']] = BusRoute::updateOrCreate(
                ['route_number' => $r['route_number']],
                $r
            );
        }

        // Helper function to convert time string (e.g. "06:30 AM") to minutes from midnight
        $timeToMin = function (string $timeStr): int {
            $timeStr = trim($timeStr);
            $parts = explode(' ', $timeStr);
            $timeParts = explode(':', $parts[0]);
            $hours = (int) $timeParts[0];
            $minutes = (int) ($timeParts[1] ?? 0);
            $ampm = strtoupper($parts[1] ?? 'AM');

            if ($ampm === 'PM' && $hours < 12) {
                $hours += 12;
            } elseif ($ampm === 'AM' && $hours === 12) {
                $hours = 0;
            }

            return $hours * 60 + $minutes;
        };

        // Helper to register trip and its sequential stop times
        $registerTrip = function (BusRoute $route, string $tripCode, string $nameEn, string $nameSi, string $direction, array $stopsTimeline) use ($stopModels, $timeToMin): void {
            $firstHalt = $stopsTimeline[0];
            $lastHalt = end($stopsTimeline);

            $originDepTime = $firstHalt['dep'];
            $originDepMin = $timeToMin($originDepTime);
            $destArrTime = $lastHalt['arr'];
            $destArrMin = $timeToMin($destArrTime);

            $trip = BusTrip::updateOrCreate(
                ['trip_code' => $tripCode],
                [
                    'bus_route_id' => $route->id,
                    'trip_name_en' => $nameEn,
                    'trip_name_si' => $nameSi,
                    'direction' => $direction,
                    'service_days' => 'all',
                    'origin_departure_time' => $originDepTime,
                    'origin_departure_minutes' => $originDepMin,
                    'destination_arrival_time' => $destArrTime,
                    'destination_arrival_minutes' => $destArrMin,
                    'is_active' => true,
                ]
            );

            // Re-create stop times for this trip
            BusStopTime::where('bus_trip_id', $trip->id)->delete();

            $seq = 1;
            foreach ($stopsTimeline as $h) {
                $stopCode = $h['code'];
                $stop = $stopModels[$stopCode] ?? null;
                if (! $stop) {
                    continue;
                }

                $arrTime = $h['arr'];
                $depTime = $h['dep'];
                $arrMin = $timeToMin($arrTime);
                $depMin = $timeToMin($depTime);

                BusStopTime::create([
                    'bus_trip_id' => $trip->id,
                    'bus_stop_id' => $stop->id,
                    'stop_sequence' => $seq++,
                    'arrival_time' => $arrTime,
                    'departure_time' => $depTime,
                    'arrival_minutes' => $arrMin,
                    'departure_minutes' => $depMin,
                    'remarks' => $h['remarks'] ?? null,
                ]);
            }
        };

        // ---------------------------------------------------------------------
        // 3. Populate Bus Trips & Timetable Schedules (The 5 Main Routes)
        // ---------------------------------------------------------------------

        // === ROUTE 1: 422 (Kaduruwela - Bakamuna - Elahera - Moragahakanda - Matale) ===
        $r422 = $routeModels['422'];

        // 422 Outbound (Kaduruwela -> Bakamuna -> Moragahakanda -> Matale)
        $r422Outbound = [
            ['code' => 'KADURUWELA', 'arr' => '05:55 AM', 'dep' => '06:00 AM'],
            ['code' => 'BAKAMUNA', 'arr' => '06:45 AM', 'dep' => '06:50 AM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '07:15 AM', 'dep' => '07:20 AM'],
            ['code' => 'MORAGAHAKANDA', 'arr' => '07:35 AM', 'dep' => '07:40 AM'],
            ['code' => 'NAULA', 'arr' => '08:20 AM', 'dep' => '08:25 AM'],
            ['code' => 'MATALE', 'arr' => '09:00 AM', 'dep' => '09:05 AM'],
        ];
        $registerTrip($r422, '422_OUT_0600', 'Kaduruwela to Matale (Morning 06:00)', 'කඳුරුවෙල සිට මාතලේ (උදෑසන 06:00)', 'outbound', $r422Outbound);

        $r422Outbound2 = [
            ['code' => 'KADURUWELA', 'arr' => '08:25 AM', 'dep' => '08:30 AM'],
            ['code' => 'BAKAMUNA', 'arr' => '09:15 AM', 'dep' => '09:20 AM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '09:45 AM', 'dep' => '09:50 AM'],
            ['code' => 'MORAGAHAKANDA', 'arr' => '10:05 AM', 'dep' => '10:10 AM'],
            ['code' => 'NAULA', 'arr' => '10:50 AM', 'dep' => '10:55 AM'],
            ['code' => 'MATALE', 'arr' => '11:30 AM', 'dep' => '11:35 AM'],
        ];
        $registerTrip($r422, '422_OUT_0830', 'Kaduruwela to Matale (Mid-Morning 08:30)', 'කඳුරුවෙල සිට මාතලේ (පෙරවරු 08:30)', 'outbound', $r422Outbound2);

        $r422Outbound3 = [
            ['code' => 'KADURUWELA', 'arr' => '11:25 AM', 'dep' => '11:30 AM'],
            ['code' => 'BAKAMUNA', 'arr' => '12:15 PM', 'dep' => '12:20 PM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '12:45 PM', 'dep' => '12:50 PM'],
            ['code' => 'MORAGAHAKANDA', 'arr' => '01:05 PM', 'dep' => '01:10 PM'],
            ['code' => 'NAULA', 'arr' => '01:50 PM', 'dep' => '01:55 PM'],
            ['code' => 'MATALE', 'arr' => '02:30 PM', 'dep' => '02:35 PM'],
        ];
        $registerTrip($r422, '422_OUT_1130', 'Kaduruwela to Matale (Noon 11:30)', 'කඳුරුවෙල සිට මාතලේ (දහවල් 11:30)', 'outbound', $r422Outbound3);

        $r422Outbound4 = [
            ['code' => 'KADURUWELA', 'arr' => '02:10 PM', 'dep' => '02:15 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '03:00 PM', 'dep' => '03:05 PM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '03:30 PM', 'dep' => '03:35 PM'],
            ['code' => 'MORAGAHAKANDA', 'arr' => '03:50 PM', 'dep' => '03:55 PM'],
            ['code' => 'NAULA', 'arr' => '04:35 PM', 'dep' => '04:40 PM'],
            ['code' => 'MATALE', 'arr' => '05:15 PM', 'dep' => '05:20 PM'],
        ];
        $registerTrip($r422, '422_OUT_1415', 'Kaduruwela to Matale (Afternoon 02:15)', 'කඳුරුවෙල සිට මාතලේ (පස්වරු 02:15)', 'outbound', $r422Outbound4);

        $r422Outbound5 = [
            ['code' => 'KADURUWELA', 'arr' => '04:40 PM', 'dep' => '04:45 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '05:30 PM', 'dep' => '05:35 PM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '06:00 PM', 'dep' => '06:05 PM'],
            ['code' => 'MORAGAHAKANDA', 'arr' => '06:20 PM', 'dep' => '06:25 PM'],
            ['code' => 'NAULA', 'arr' => '07:05 PM', 'dep' => '07:10 PM'],
            ['code' => 'MATALE', 'arr' => '07:45 PM', 'dep' => '07:50 PM'],
        ];
        $registerTrip($r422, '422_OUT_1645', 'Kaduruwela to Matale (Evening 04:45)', 'කඳුරුවෙල සිට මාතලේ (සවස 04:45)', 'outbound', $r422Outbound5);

        // 422 Inbound (Matale -> Moragahakanda -> Elahera -> Bakamuna -> Kaduruwela)
        $r422Inbound = [
            ['code' => 'MATALE', 'arr' => '06:25 AM', 'dep' => '06:30 AM'],
            ['code' => 'NAULA', 'arr' => '07:05 AM', 'dep' => '07:10 AM'],
            ['code' => 'MORAGAHAKANDA', 'arr' => '07:50 AM', 'dep' => '07:55 AM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '08:10 AM', 'dep' => '08:15 AM'],
            ['code' => 'BAKAMUNA', 'arr' => '08:40 AM', 'dep' => '08:45 AM'],
            ['code' => 'KADURUWELA', 'arr' => '09:30 AM', 'dep' => '09:35 AM'],
        ];
        $registerTrip($r422, '422_IN_0630', 'Matale to Kaduruwela (Morning 06:30)', 'මාතලේ සිට කඳුරුවෙල (උදෑසන 06:30)', 'inbound', $r422Inbound);

        $r422Inbound2 = [
            ['code' => 'MATALE', 'arr' => '08:55 AM', 'dep' => '09:00 AM'],
            ['code' => 'NAULA', 'arr' => '09:35 AM', 'dep' => '09:40 AM'],
            ['code' => 'MORAGAHAKANDA', 'arr' => '10:20 AM', 'dep' => '10:25 AM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '10:40 AM', 'dep' => '10:45 AM'],
            ['code' => 'BAKAMUNA', 'arr' => '11:10 AM', 'dep' => '11:15 AM'],
            ['code' => 'KADURUWELA', 'arr' => '12:00 PM', 'dep' => '12:05 PM'],
        ];
        $registerTrip($r422, '422_IN_0900', 'Matale to Kaduruwela (Mid-Morning 09:00)', 'මාතලේ සිට කඳුරුවෙල (පෙරවරු 09:00)', 'inbound', $r422Inbound2);

        $r422Inbound3 = [
            ['code' => 'MATALE', 'arr' => '12:10 PM', 'dep' => '12:15 PM'],
            ['code' => 'NAULA', 'arr' => '12:50 PM', 'dep' => '12:55 PM'],
            ['code' => 'MORAGAHAKANDA', 'arr' => '01:35 PM', 'dep' => '01:40 PM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '01:55 PM', 'dep' => '02:00 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '02:25 PM', 'dep' => '02:30 PM'],
            ['code' => 'KADURUWELA', 'arr' => '03:15 PM', 'dep' => '03:20 PM'],
        ];
        $registerTrip($r422, '422_IN_1215', 'Matale to Kaduruwela (Noon 12:15)', 'මාතලේ සිට කඳුරුවෙල (දහවල් 12:15)', 'inbound', $r422Inbound3);

        $r422Inbound4 = [
            ['code' => 'MATALE', 'arr' => '02:40 PM', 'dep' => '02:45 PM'],
            ['code' => 'NAULA', 'arr' => '03:20 PM', 'dep' => '03:25 PM'],
            ['code' => 'MORAGAHAKANDA', 'arr' => '04:05 PM', 'dep' => '04:10 PM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '04:25 PM', 'dep' => '04:30 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '04:55 PM', 'dep' => '05:00 PM'],
            ['code' => 'KADURUWELA', 'arr' => '05:45 PM', 'dep' => '05:50 PM'],
        ];
        $registerTrip($r422, '422_IN_1445', 'Matale to Kaduruwela (Afternoon 02:45)', 'මාතලේ සිට කඳුරුවෙල (පස්වරු 02:45)', 'inbound', $r422Inbound4);

        $r422Inbound5 = [
            ['code' => 'MATALE', 'arr' => '05:10 PM', 'dep' => '05:15 PM'],
            ['code' => 'NAULA', 'arr' => '05:50 PM', 'dep' => '05:55 PM'],
            ['code' => 'MORAGAHAKANDA', 'arr' => '06:35 PM', 'dep' => '06:40 PM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '06:55 PM', 'dep' => '07:00 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '07:25 PM', 'dep' => '07:30 PM'],
            ['code' => 'KADURUWELA', 'arr' => '08:15 PM', 'dep' => '08:20 PM'],
        ];
        $registerTrip($r422, '422_IN_1715', 'Matale to Kaduruwela (Evening 05:15)', 'මාතලේ සිට කඳුරුවෙල (සවස 05:15)', 'inbound', $r422Inbound5);

        // === ROUTE 2: 45 (Matale - Elahera - Polathuwadiya - Wilgamuwa) ===
        $r45 = $routeModels['45'];

        $r45Outbound1 = [
            ['code' => 'MATALE', 'arr' => '06:55 AM', 'dep' => '07:00 AM'],
            ['code' => 'NAULA', 'arr' => '07:35 AM', 'dep' => '07:40 AM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '08:20 AM', 'dep' => '08:25 AM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '08:45 AM', 'dep' => '08:50 AM'],
            ['code' => 'WILGAMUWA', 'arr' => '09:25 AM', 'dep' => '09:30 AM'],
        ];
        $registerTrip($r45, '45_OUT_0700', 'Matale to Wilgamuwa (Morning 07:00)', 'මාතලේ සිට විල්ගමුව (උදෑසන 07:00)', 'outbound', $r45Outbound1);

        $r45Outbound2 = [
            ['code' => 'MATALE', 'arr' => '10:10 AM', 'dep' => '10:15 AM'],
            ['code' => 'NAULA', 'arr' => '10:50 AM', 'dep' => '10:55 AM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '11:35 AM', 'dep' => '11:40 AM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '12:00 PM', 'dep' => '12:05 PM'],
            ['code' => 'WILGAMUWA', 'arr' => '12:40 PM', 'dep' => '12:45 PM'],
        ];
        $registerTrip($r45, '45_OUT_1015', 'Matale to Wilgamuwa (Mid-Day 10:15)', 'මාතලේ සිට විල්ගමුව (පෙරවරු 10:15)', 'outbound', $r45Outbound2);

        $r45Outbound3 = [
            ['code' => 'MATALE', 'arr' => '01:25 PM', 'dep' => '01:30 PM'],
            ['code' => 'NAULA', 'arr' => '02:05 PM', 'dep' => '02:10 PM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '02:50 PM', 'dep' => '02:55 PM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '03:15 PM', 'dep' => '03:20 PM'],
            ['code' => 'WILGAMUWA', 'arr' => '03:55 PM', 'dep' => '04:00 PM'],
        ];
        $registerTrip($r45, '45_OUT_1330', 'Matale to Wilgamuwa (Afternoon 01:30)', 'මාතලේ සිට විල්ගමුව (පස්වරු 01:30)', 'outbound', $r45Outbound3);

        $r45Outbound4 = [
            ['code' => 'MATALE', 'arr' => '04:25 PM', 'dep' => '04:30 PM'],
            ['code' => 'NAULA', 'arr' => '05:05 PM', 'dep' => '05:10 PM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '05:50 PM', 'dep' => '05:55 PM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '06:15 PM', 'dep' => '06:20 PM'],
            ['code' => 'WILGAMUWA', 'arr' => '06:55 PM', 'dep' => '07:00 PM'],
        ];
        $registerTrip($r45, '45_OUT_1630', 'Matale to Wilgamuwa (Evening 04:30)', 'මාතලේ සිට විල්ගමුව (සවස 04:30)', 'outbound', $r45Outbound4);

        // 45 Inbound (Wilgamuwa -> Polathuwadiya -> Elahera -> Naula -> Matale)
        $r45Inbound1 = [
            ['code' => 'WILGAMUWA', 'arr' => '06:10 AM', 'dep' => '06:15 AM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '06:50 AM', 'dep' => '06:55 AM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '07:15 AM', 'dep' => '07:20 AM'],
            ['code' => 'NAULA', 'arr' => '08:00 AM', 'dep' => '08:05 AM'],
            ['code' => 'MATALE', 'arr' => '08:40 AM', 'dep' => '08:45 AM'],
        ];
        $registerTrip($r45, '45_IN_0615', 'Wilgamuwa to Matale (Morning 06:15)', 'විල්ගමුව සිට මාතලේ (උදෑසන 06:15)', 'inbound', $r45Inbound1);

        $r45Inbound2 = [
            ['code' => 'WILGAMUWA', 'arr' => '09:40 AM', 'dep' => '09:45 AM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '10:20 AM', 'dep' => '10:25 AM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '10:45 AM', 'dep' => '10:50 AM'],
            ['code' => 'NAULA', 'arr' => '11:30 AM', 'dep' => '11:35 AM'],
            ['code' => 'MATALE', 'arr' => '12:10 PM', 'dep' => '12:15 PM'],
        ];
        $registerTrip($r45, '45_IN_0945', 'Wilgamuwa to Matale (Mid-Morning 09:45)', 'විල්ගමුව සිට මාතලේ (පෙරවරු 09:45)', 'inbound', $r45Inbound2);

        $r45Inbound3 = [
            ['code' => 'WILGAMUWA', 'arr' => '12:55 PM', 'dep' => '01:00 PM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '01:35 PM', 'dep' => '01:40 PM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '02:00 PM', 'dep' => '02:05 PM'],
            ['code' => 'NAULA', 'arr' => '02:45 PM', 'dep' => '02:50 PM'],
            ['code' => 'MATALE', 'arr' => '03:25 PM', 'dep' => '03:30 PM'],
        ];
        $registerTrip($r45, '45_IN_1300', 'Wilgamuwa to Matale (Afternoon 01:00)', 'විල්ගමුව සිට මාතලේ (පස්වරු 01:00)', 'inbound', $r45Inbound3);

        $r45Inbound4 = [
            ['code' => 'WILGAMUWA', 'arr' => '04:10 PM', 'dep' => '04:15 PM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '04:50 PM', 'dep' => '04:55 PM'],
            ['code' => 'ELAHERA_JNC', 'arr' => '05:15 PM', 'dep' => '05:20 PM'],
            ['code' => 'NAULA', 'arr' => '06:00 PM', 'dep' => '06:05 PM'],
            ['code' => 'MATALE', 'arr' => '06:40 PM', 'dep' => '06:45 PM'],
        ];
        $registerTrip($r45, '45_IN_1615', 'Wilgamuwa to Matale (Evening 04:15)', 'විල්ගමුව සිට මාතලේ (සවස 04:15)', 'inbound', $r45Inbound4);

        // === ROUTE 3: 35 (Mahiyanganaya - Wilgamuwa - Polathuwadiya - Kumara Ella - Dambulla) ===
        $r35 = $routeModels['35'];

        $r35Outbound1 = [
            ['code' => 'MAHIYANGANAYA', 'arr' => '06:40 AM', 'dep' => '06:45 AM'],
            ['code' => 'WILGAMUWA', 'arr' => '07:40 AM', 'dep' => '07:45 AM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '08:15 AM', 'dep' => '08:20 AM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '08:40 AM', 'dep' => '08:45 AM'],
            ['code' => 'DAMBULLA', 'arr' => '09:30 AM', 'dep' => '09:35 AM'],
        ];
        $registerTrip($r35, '35_OUT_0645', 'Mahiyanganaya to Dambulla (Morning 06:45)', 'මහියංගනය සිට දඹුල්ල (උදෑසන 06:45)', 'outbound', $r35Outbound1);

        $r35Outbound2 = [
            ['code' => 'MAHIYANGANAYA', 'arr' => '09:10 AM', 'dep' => '09:15 AM'],
            ['code' => 'WILGAMUWA', 'arr' => '10:10 AM', 'dep' => '10:15 AM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '10:45 AM', 'dep' => '10:50 AM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '11:10 AM', 'dep' => '11:15 AM'],
            ['code' => 'DAMBULLA', 'arr' => '12:00 PM', 'dep' => '12:05 PM'],
        ];
        $registerTrip($r35, '35_OUT_0915', 'Mahiyanganaya to Dambulla (Mid-Morning 09:15)', 'මහියංගනය සිට දඹුල්ල (පෙරවරු 09:15)', 'outbound', $r35Outbound2);

        $r35Outbound3 = [
            ['code' => 'MAHIYANGANAYA', 'arr' => '12:40 PM', 'dep' => '12:45 PM'],
            ['code' => 'WILGAMUWA', 'arr' => '01:40 PM', 'dep' => '01:45 PM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '02:15 PM', 'dep' => '02:20 PM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '02:40 PM', 'dep' => '02:45 PM'],
            ['code' => 'DAMBULLA', 'arr' => '03:30 PM', 'dep' => '03:35 PM'],
        ];
        $registerTrip($r35, '35_OUT_1245', 'Mahiyanganaya to Dambulla (Afternoon 12:45)', 'මහියංගනය සිට දඹුල්ල (දහවල් 12:45)', 'outbound', $r35Outbound3);

        $r35Outbound4 = [
            ['code' => 'MAHIYANGANAYA', 'arr' => '03:25 PM', 'dep' => '03:30 PM'],
            ['code' => 'WILGAMUWA', 'arr' => '04:25 PM', 'dep' => '04:30 PM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '05:00 PM', 'dep' => '05:05 PM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '05:25 PM', 'dep' => '05:30 PM'],
            ['code' => 'DAMBULLA', 'arr' => '06:15 PM', 'dep' => '06:20 PM'],
        ];
        $registerTrip($r35, '35_OUT_1530', 'Mahiyanganaya to Dambulla (Evening 03:30)', 'මහියංගනය සිට දඹුල්ල (සවස 03:30)', 'outbound', $r35Outbound4);

        // 35 Inbound (Dambulla -> Kumara Ella -> Polathuwadiya -> Wilgamuwa -> Mahiyanganaya)
        $r35Inbound1 = [
            ['code' => 'DAMBULLA', 'arr' => '07:25 AM', 'dep' => '07:30 AM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '08:20 AM', 'dep' => '08:25 AM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '08:45 AM', 'dep' => '08:50 AM'],
            ['code' => 'WILGAMUWA', 'arr' => '09:20 AM', 'dep' => '09:25 AM'],
            ['code' => 'MAHIYANGANAYA', 'arr' => '10:20 AM', 'dep' => '10:25 AM'],
        ];
        $registerTrip($r35, '35_IN_0730', 'Dambulla to Mahiyanganaya (Morning 07:30)', 'දඹුල්ල සිට මහියංගනය (උදෑසන 07:30)', 'inbound', $r35Inbound1);

        $r35Inbound2 = [
            ['code' => 'DAMBULLA', 'arr' => '10:25 AM', 'dep' => '10:30 AM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '11:20 AM', 'dep' => '11:25 AM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '11:45 AM', 'dep' => '11:50 AM'],
            ['code' => 'WILGAMUWA', 'arr' => '12:20 PM', 'dep' => '12:25 PM'],
            ['code' => 'MAHIYANGANAYA', 'arr' => '01:20 PM', 'dep' => '01:25 PM'],
        ];
        $registerTrip($r35, '35_IN_1030', 'Dambulla to Mahiyanganaya (Mid-Morning 10:30)', 'දඹුල්ල සිට මහියංගනය (පෙරවරු 10:30)', 'inbound', $r35Inbound2);

        $r35Inbound3 = [
            ['code' => 'DAMBULLA', 'arr' => '01:40 PM', 'dep' => '01:45 PM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '02:35 PM', 'dep' => '02:40 PM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '03:00 PM', 'dep' => '03:05 PM'],
            ['code' => 'WILGAMUWA', 'arr' => '03:35 PM', 'dep' => '03:40 PM'],
            ['code' => 'MAHIYANGANAYA', 'arr' => '04:35 PM', 'dep' => '04:40 PM'],
        ];
        $registerTrip($r35, '35_IN_1345', 'Dambulla to Mahiyanganaya (Afternoon 01:45)', 'දඹුල්ල සිට මහියංගනය (දහවල් 01:45)', 'inbound', $r35Inbound3);

        $r35Inbound4 = [
            ['code' => 'DAMBULLA', 'arr' => '04:40 PM', 'dep' => '04:45 PM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '05:35 PM', 'dep' => '05:40 PM'],
            ['code' => 'POLATHUWADIYA', 'arr' => '06:00 PM', 'dep' => '06:05 PM'],
            ['code' => 'WILGAMUWA', 'arr' => '06:35 PM', 'dep' => '06:40 PM'],
            ['code' => 'MAHIYANGANAYA', 'arr' => '07:35 PM', 'dep' => '07:40 PM'],
        ];
        $registerTrip($r35, '35_IN_1645', 'Dambulla to Mahiyanganaya (Evening 04:45)', 'දඹුල්ල සිට මහියංගනය (සවස 04:45)', 'inbound', $r35Inbound4);

        // === ROUTE 4: 550 (Polonnaruwa - Bakamuna - Elahera Anicut) ===
        $r550 = $routeModels['550'];

        $r550Outbound1 = [
            ['code' => 'KADURUWELA', 'arr' => '06:10 AM', 'dep' => '06:15 AM'],
            ['code' => 'BAKAMUNA', 'arr' => '07:05 AM', 'dep' => '07:10 AM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '07:20 AM', 'dep' => '07:25 AM'],
            ['code' => 'ELAHERA_ANICUT', 'arr' => '07:45 AM', 'dep' => '07:50 AM'],
        ];
        $registerTrip($r550, '550_OUT_0615', 'Polonnaruwa to Elahera Anicut (06:15)', 'පොළොන්නරුව සිට ඇලහැර අමුණ (06:15)', 'outbound', $r550Outbound1);

        $r550Outbound2 = [
            ['code' => 'KADURUWELA', 'arr' => '08:40 AM', 'dep' => '08:45 AM'],
            ['code' => 'BAKAMUNA', 'arr' => '09:35 AM', 'dep' => '09:40 AM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '09:50 AM', 'dep' => '09:55 AM'],
            ['code' => 'ELAHERA_ANICUT', 'arr' => '10:15 AM', 'dep' => '10:20 AM'],
        ];
        $registerTrip($r550, '550_OUT_0845', 'Polonnaruwa to Elahera Anicut (08:45)', 'පොළොන්නරුව සිට ඇලහැර අමුණ (08:45)', 'outbound', $r550Outbound2);

        $r550Outbound3 = [
            ['code' => 'KADURUWELA', 'arr' => '11:40 AM', 'dep' => '11:45 AM'],
            ['code' => 'BAKAMUNA', 'arr' => '12:35 PM', 'dep' => '12:40 PM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '12:50 PM', 'dep' => '12:55 PM'],
            ['code' => 'ELAHERA_ANICUT', 'arr' => '01:15 PM', 'dep' => '01:20 PM'],
        ];
        $registerTrip($r550, '550_OUT_1145', 'Polonnaruwa to Elahera Anicut (11:45)', 'පොළොන්නරුව සිට ඇලහැර අමුණ (11:45)', 'outbound', $r550Outbound3);

        $r550Outbound4 = [
            ['code' => 'KADURUWELA', 'arr' => '02:25 PM', 'dep' => '02:30 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '03:20 PM', 'dep' => '03:25 PM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '03:35 PM', 'dep' => '03:40 PM'],
            ['code' => 'ELAHERA_ANICUT', 'arr' => '04:00 PM', 'dep' => '04:05 PM'],
        ];
        $registerTrip($r550, '550_OUT_1430', 'Polonnaruwa to Elahera Anicut (02:30)', 'පොළොන්නරුව සිට ඇලහැර අමුණ (02:30)', 'outbound', $r550Outbound4);

        $r550Outbound5 = [
            ['code' => 'KADURUWELA', 'arr' => '04:55 PM', 'dep' => '05:00 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '05:50 PM', 'dep' => '05:55 PM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '06:05 PM', 'dep' => '06:10 PM'],
            ['code' => 'ELAHERA_ANICUT', 'arr' => '06:30 PM', 'dep' => '06:35 PM'],
        ];
        $registerTrip($r550, '550_OUT_1700', 'Polonnaruwa to Elahera Anicut (05:00)', 'පොළොන්නරුව සිට ඇලහැර අමුණ (05:00)', 'outbound', $r550Outbound5);

        // 550 Inbound (Elahera Anicut -> Kottapitiya -> Bakamuna -> Kaduruwela)
        $r550Inbound1 = [
            ['code' => 'ELAHERA_ANICUT', 'arr' => '07:55 AM', 'dep' => '08:00 AM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '08:15 AM', 'dep' => '08:20 AM'],
            ['code' => 'BAKAMUNA', 'arr' => '08:30 AM', 'dep' => '08:35 AM'],
            ['code' => 'KADURUWELA', 'arr' => '09:20 AM', 'dep' => '09:25 AM'],
        ];
        $registerTrip($r550, '550_IN_0800', 'Elahera Anicut to Polonnaruwa (08:00)', 'ඇලහැර අමුණ සිට පොළොන්නරුව (08:00)', 'inbound', $r550Inbound1);

        $r550Inbound2 = [
            ['code' => 'ELAHERA_ANICUT', 'arr' => '10:25 AM', 'dep' => '10:30 AM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '10:45 AM', 'dep' => '10:50 AM'],
            ['code' => 'BAKAMUNA', 'arr' => '11:00 AM', 'dep' => '11:05 AM'],
            ['code' => 'KADURUWELA', 'arr' => '11:50 AM', 'dep' => '11:55 AM'],
        ];
        $registerTrip($r550, '550_IN_1030', 'Elahera Anicut to Polonnaruwa (10:30)', 'ඇලහැර අමුණ සිට පොළොන්නරුව (10:30)', 'inbound', $r550Inbound2);

        $r550Inbound3 = [
            ['code' => 'ELAHERA_ANICUT', 'arr' => '01:25 PM', 'dep' => '01:30 PM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '01:45 PM', 'dep' => '01:50 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '02:00 PM', 'dep' => '02:05 PM'],
            ['code' => 'KADURUWELA', 'arr' => '02:50 PM', 'dep' => '02:55 PM'],
        ];
        $registerTrip($r550, '550_IN_1330', 'Elahera Anicut to Polonnaruwa (01:30)', 'ඇලහැර අමුණ සිට පොළොන්නරුව (01:30)', 'inbound', $r550Inbound3);

        $r550Inbound4 = [
            ['code' => 'ELAHERA_ANICUT', 'arr' => '04:15 PM', 'dep' => '04:20 PM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '04:35 PM', 'dep' => '04:40 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '04:50 PM', 'dep' => '04:55 PM'],
            ['code' => 'KADURUWELA', 'arr' => '05:40 PM', 'dep' => '05:45 PM'],
        ];
        $registerTrip($r550, '550_IN_1620', 'Elahera Anicut to Polonnaruwa (04:20)', 'ඇලහැර අමුණ සිට පොළොන්නරුව (04:20)', 'inbound', $r550Inbound4);

        $r550Inbound5 = [
            ['code' => 'ELAHERA_ANICUT', 'arr' => '06:40 PM', 'dep' => '06:45 PM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '07:00 PM', 'dep' => '07:05 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '07:15 PM', 'dep' => '07:20 PM'],
            ['code' => 'KADURUWELA', 'arr' => '08:05 PM', 'dep' => '08:10 PM'],
        ];
        $registerTrip($r550, '550_IN_1845', 'Elahera Anicut to Polonnaruwa (06:45)', 'ඇලහැර අමුණ සිට පොළොන්නරුව (06:45)', 'inbound', $r550Inbound5);

        // === ROUTE 5: 422/2 (Bakamuna - Kottapitiya - Kumara Ella - Dambulla) ===
        $r422_2 = $routeModels['422/2'];

        $r422_2Outbound1 = [
            ['code' => 'BAKAMUNA', 'arr' => '07:10 AM', 'dep' => '07:15 AM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '07:30 AM', 'dep' => '07:35 AM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '07:55 AM', 'dep' => '08:00 AM'],
            ['code' => 'DAMBULLA', 'arr' => '08:45 AM', 'dep' => '08:50 AM'],
        ];
        $registerTrip($r422_2, '422_2_OUT_0715', 'Bakamuna to Dambulla via Kumara Ella (07:15)', 'බකමූණ සිට දඹුල්ල (කුමාර ඇල්ල හරහා 07:15)', 'outbound', $r422_2Outbound1);

        $r422_2Outbound2 = [
            ['code' => 'BAKAMUNA', 'arr' => '09:25 AM', 'dep' => '09:30 AM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '09:45 AM', 'dep' => '09:50 AM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '10:10 AM', 'dep' => '10:15 AM'],
            ['code' => 'DAMBULLA', 'arr' => '11:00 AM', 'dep' => '11:05 AM'],
        ];
        $registerTrip($r422_2, '422_2_OUT_0930', 'Bakamuna to Dambulla via Kumara Ella (09:30)', 'බකමූණ සිට දඹුල්ල (කුමාර ඇල්ල හරහා 09:30)', 'outbound', $r422_2Outbound2);

        $r422_2Outbound3 = [
            ['code' => 'BAKAMUNA', 'arr' => '12:25 PM', 'dep' => '12:30 PM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '12:45 PM', 'dep' => '12:50 PM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '01:10 PM', 'dep' => '01:15 PM'],
            ['code' => 'DAMBULLA', 'arr' => '02:00 PM', 'dep' => '02:05 PM'],
        ];
        $registerTrip($r422_2, '422_2_OUT_1230', 'Bakamuna to Dambulla via Kumara Ella (12:30)', 'බකමූණ සිට දඹුල්ල (කුමාර ඇල්ල හරහා 12:30)', 'outbound', $r422_2Outbound3);

        $r422_2Outbound4 = [
            ['code' => 'BAKAMUNA', 'arr' => '03:10 PM', 'dep' => '03:15 PM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '03:30 PM', 'dep' => '03:35 PM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '03:55 PM', 'dep' => '04:00 PM'],
            ['code' => 'DAMBULLA', 'arr' => '04:45 PM', 'dep' => '04:50 PM'],
        ];
        $registerTrip($r422_2, '422_2_OUT_1515', 'Bakamuna to Dambulla via Kumara Ella (03:15)', 'බකමූණ සිට දඹුල්ල (කුමාර ඇල්ල හරහා 03:15)', 'outbound', $r422_2Outbound4);

        $r422_2Outbound5 = [
            ['code' => 'BAKAMUNA', 'arr' => '05:25 PM', 'dep' => '05:30 PM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '05:45 PM', 'dep' => '05:50 PM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '06:10 PM', 'dep' => '06:15 PM'],
            ['code' => 'DAMBULLA', 'arr' => '07:00 PM', 'dep' => '07:05 PM'],
        ];
        $registerTrip($r422_2, '422_2_OUT_1730', 'Bakamuna to Dambulla via Kumara Ella (05:30)', 'බකමූණ සිට දඹුල්ල (කුමාර ඇල්ල හරහා 05:30)', 'outbound', $r422_2Outbound5);

        // 422/2 Inbound (Dambulla -> Kumara Ella -> Kottapitiya -> Bakamuna)
        $r422_2Inbound1 = [
            ['code' => 'DAMBULLA', 'arr' => '08:45 AM', 'dep' => '08:50 AM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '09:35 AM', 'dep' => '09:40 AM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '10:00 AM', 'dep' => '10:05 AM'],
            ['code' => 'BAKAMUNA', 'arr' => '10:20 AM', 'dep' => '10:25 AM'],
        ];
        $registerTrip($r422_2, '422_2_IN_0850', 'Dambulla to Bakamuna via Kumara Ella (08:50)', 'දඹුල්ල සිට බකමූණ (කුමාර ඇල්ල හරහා 08:50)', 'inbound', $r422_2Inbound1);

        $r422_2Inbound2 = [
            ['code' => 'DAMBULLA', 'arr' => '11:10 AM', 'dep' => '11:15 AM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '12:00 PM', 'dep' => '12:05 PM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '12:25 PM', 'dep' => '12:30 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '12:45 PM', 'dep' => '12:50 PM'],
        ];
        $registerTrip($r422_2, '422_2_IN_1115', 'Dambulla to Bakamuna via Kumara Ella (11:15)', 'දඹුල්ල සිට බකමූණ (කුමාර ඇල්ල හරහා 11:15)', 'inbound', $r422_2Inbound2);

        $r422_2Inbound3 = [
            ['code' => 'DAMBULLA', 'arr' => '02:10 PM', 'dep' => '02:15 PM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '03:00 PM', 'dep' => '03:05 PM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '03:25 PM', 'dep' => '03:30 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '03:45 PM', 'dep' => '03:50 PM'],
        ];
        $registerTrip($r422_2, '422_2_IN_1415', 'Dambulla to Bakamuna via Kumara Ella (02:15)', 'දඹුල්ල සිට බකමූණ (කුමාර ඇල්ල හරහා 02:15)', 'inbound', $r422_2Inbound3);

        $r422_2Inbound4 = [
            ['code' => 'DAMBULLA', 'arr' => '04:55 PM', 'dep' => '05:00 PM'],
            ['code' => 'KUMARA_ELLA', 'arr' => '05:45 PM', 'dep' => '05:50 PM'],
            ['code' => 'KOTTAPITIYA', 'arr' => '06:10 PM', 'dep' => '06:15 PM'],
            ['code' => 'BAKAMUNA', 'arr' => '06:30 PM', 'dep' => '06:35 PM'],
        ];
        $registerTrip($r422_2, '422_2_IN_1700', 'Dambulla to Bakamuna via Kumara Ella (05:00)', 'දඹුල්ල සිට බකමූණ (කුමාර ඇල්ල හරහා 05:00)', 'inbound', $r422_2Inbound4);

        // ---------------------------------------------------------------------
        // 4. Synchronize with legacy BusRouteHalt table for backwards compatibility
        // ---------------------------------------------------------------------
        foreach ($routeModels as $route) {
            BusRouteHalt::where('bus_route_id', $route->id)->delete();
            $firstTrip = BusTrip::where('bus_route_id', $route->id)->where('direction', 'outbound')->first();
            if ($firstTrip) {
                foreach ($firstTrip->stopTimes as $st) {
                    BusRouteHalt::create([
                        'bus_route_id' => $route->id,
                        'bus_stop_id' => $st->bus_stop_id,
                        'stop_name' => $st->busStop->name_en,
                        'arrival_time' => $st->arrival_time,
                        'departure_time' => $st->departure_time,
                        'stop_order' => $st->stop_sequence,
                        'remarks' => $st->busStop->getLocalizedName(),
                    ]);
                }
            }
        }

        // ---------------------------------------------------------------------
        // 5. Seed Tourist Destinations & Associate to Nearest Bus Stops
        // ---------------------------------------------------------------------
        $destinationsData = [
            [
                'title_en' => 'Moragahakanda Reservoir & Dam Gallery',
                'title_si' => 'මොරගහකන්ද ජලාශය සහ වේල්ල නැරඹුම් මැදිරිය',
                'slug' => 'moragahakanda-reservoir',
                'short_description_en' => 'Largest multi-purpose reservoir in the Mahaweli irrigation corridor featuring panoramic views and an impressive spillway.',
                'short_description_si' => 'මහවැලි සංවර්ධන යෝජනා ක්‍රමයේ දැවැන්තම ජලාශය වන මොරගහකන්ද මනරම් කඳු වළල්ලකින් වටවී ඇත.',
                'description_en' => 'Moragahakanda Dam is a high-gravity dam built across the Amban Ganga at Elahera. The reservoir serves as the primary irrigation headworks for Rajarata and northern agricultural basins.',
                'description_si' => 'මොරගහකන්ද ජලාශය අඹන් ගඟ හරහා ඉදිකරන ලද දැවැන්ත ජලාශයකි. මෙහි වේල්ල නැරඹුම් මැදිරිය, පිටවාන සහ දර්ශනීය කඳු වැටි සංචාරකයන්ගේ ප්‍රධාන ආකර්ෂණය වේ.',
                'category' => 'Canals & Lakes',
                'category_si' => 'ඇළ මාර්ග සහ ජලාශ',
                'route_zone' => 'Zone B - Elahera & Moragahakanda Route',
                'distance_km' => 24.5,
                'travel_time_minutes' => 35,
                'stay_duration_minutes' => 60,
                'nearest_bus_stop' => 'Moragahakanda Reservoir Stop',
                'nearest_bus_stop_id' => $stopModels['MORAGAHAKANDA']->id,
                'bus_stop_walk_minutes' => 5,
                'bus_route_numbers' => 'Route 422',
                'latitude' => 7.7020,
                'longitude' => 80.7650,
                'opening_time' => '07:00',
                'closing_time' => '18:00',
                'is_always_open' => false,
                'ticket_price_min' => 0,
                'ticket_price_max' => 0,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title_en' => 'Kumara Ella Ruins & Cascades',
                'title_si' => 'කුමාර ඇල්ල නටඹුන් සහ දියඇල්ල',
                'slug' => 'kumara-ella-ruins',
                'short_description_en' => 'Serene forest waterfall with natural rock pools and adjacent historic Buddhist monastic ruins.',
                'short_description_si' => 'ඝන වනාන්තරයක සැඟවුණු ස්වභාවික දිය තටාක සහ අනුරාධපුර යුගයට අයත් ගල් කණු සහිත පුරාණ නටඹුන් සංකීර්ණය.',
                'description_en' => 'Kumara Ella is a multi-tier natural forest cascade flowing through granite rock formations. Nearby stone pillars and carved moonstones testify to a secluded ancient hermitage retreat.',
                'description_si' => 'කුමාර ඇල්ල මනරම් වන පෙතක් මැද පිහිටි දියඇල්ලක් වන අතර ඒ අසල පුරාණ ආරණ්‍ය සේනාසන නටඹුන්, ගල් කණු සහ කැටයම් දැකගත හැක.',
                'category' => 'Waterfalls',
                'category_si' => 'දියඇලි',
                'route_zone' => 'Zone C - Forest & Wilderness Corridor',
                'distance_km' => 18.0,
                'travel_time_minutes' => 30,
                'stay_duration_minutes' => 60,
                'nearest_bus_stop' => 'Kumara Ella Halt',
                'nearest_bus_stop_id' => $stopModels['KUMARA_ELLA']->id,
                'bus_stop_walk_minutes' => 8,
                'bus_route_numbers' => 'Route 35, Route 422/2',
                'latitude' => 7.8200,
                'longitude' => 80.8800,
                'opening_time' => '06:30',
                'closing_time' => '17:30',
                'is_always_open' => false,
                'ticket_price_min' => 0,
                'ticket_price_max' => 0,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title_en' => 'Dambulla Royal Cave Temple',
                'title_si' => 'දඹුල්ල රන්ගිරි ලෙන් විහාරය',
                'slug' => 'dambulla-cave-temple',
                'short_description_en' => 'World Heritage rock cave monastery featuring 150+ Buddha statues and magnificent ceiling frescoes dating to King Valagamba.',
                'short_description_si' => 'වළගම්බා රජු දවස කරවන ලද විශ්මිත බිතුසිතුවම් සහ බුද්ධ ප්‍රතිමා 150කට අධික සංඛ්‍යාවක් සහිත යුනෙස්කෝ ලෝක උරුම ලෙන් සංකීර්ණය.',
                'description_en' => 'Rangiri Dambulla Cave Temple is the largest and best-preserved cave temple complex in Sri Lanka, rising 160 meters above surrounding plains.',
                'description_si' => 'දඹුල්ල ලෙන් විහාරය ලංකාවේ විශාලතම හා හොඳින්ම සංරක්ෂණය වූ ලෙන් විහාර සංකීර්ණය වන අතර විහාර ලෙන් පහකින් සමන්විත වේ.',
                'category' => 'Historical & Cultural',
                'category_si' => 'ඓතිහාසික සහ සංස්කෘතික',
                'route_zone' => 'Zone D - Cultural Triangle Link',
                'distance_km' => 42.0,
                'travel_time_minutes' => 55,
                'stay_duration_minutes' => 90,
                'nearest_bus_stop' => 'Dambulla Clock Tower Terminal',
                'nearest_bus_stop_id' => $stopModels['DAMBULLA']->id,
                'bus_stop_walk_minutes' => 10,
                'bus_route_numbers' => 'Route 35, Route 422/2',
                'latitude' => 7.8731,
                'longitude' => 80.6517,
                'opening_time' => '07:00',
                'closing_time' => '19:00',
                'is_always_open' => false,
                'ticket_price_min' => 2000,
                'ticket_price_max' => 2000,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title_en' => 'Elahera Ancient Stone Anicut (Amuna)',
                'title_si' => 'ඇලහැර පුරාණ ගල් අමුණ',
                'slug' => 'elahera-ancient-anicut',
                'short_description_en' => 'Historic stone diversion weir built by King Aggabodhi I in the 6th century to divert Amban Ganga into the Elahera canal.',
                'short_description_si' => 'ක්‍රි.ව. 6 වන සියවසේ අග්බෝ රජු විසින් අඹන් ගඟ හරස් කර තැනූ විශ්මිත හෙළ වාරි ශිල්පීය ගල් අමුණ.',
                'description_en' => 'The ancient stone weir diversion at Elahera is an engineering marvel that feeds Giritale and Kantale tanks through ancient trans-basin canals.',
                'description_si' => 'ඇලහැර අමුණ මගින් ගිරිතලේ, කන්තලේ සහ මින්නේරිය ජලාශ පෝෂණය කළ යෝධ ඇළ ආරම්භ වන ඓතිහාසික ස්ථානයයි.',
                'category' => 'Historical & Cultural',
                'category_si' => 'ඓතිහාසික සහ සංස්කෘතික',
                'route_zone' => 'Zone B - Elahera & Moragahakanda Route',
                'distance_km' => 12.0,
                'travel_time_minutes' => 20,
                'stay_duration_minutes' => 45,
                'nearest_bus_stop' => 'Elahera Anicut Halt',
                'nearest_bus_stop_id' => $stopModels['ELAHERA_ANICUT']->id,
                'bus_stop_walk_minutes' => 3,
                'bus_route_numbers' => 'Route 550',
                'latitude' => 7.7490,
                'longitude' => 80.7800,
                'opening_time' => '06:00',
                'closing_time' => '18:30',
                'is_always_open' => true,
                'ticket_price_min' => 0,
                'ticket_price_max' => 0,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title_en' => 'Angamedilla National Park Entrance',
                'title_si' => 'අංගමැඩිල්ල ජාතික වනෝද්‍යානය පිවිසුම',
                'slug' => 'angamedilla-national-park',
                'short_description_en' => 'Sanctuary protecting the catchment of Parakrama Samudra with rich elephant herds, birdlife, and ancient Minneriya feeder canal.',
                'short_description_si' => 'පරාක්‍රම සමුද්‍රයේ ජල පෝෂක ප්‍රදේශය ආරක්ෂා කරන, අලි ඇතුන් සහ දුර්ලභ පක්ෂීන් බහුල වනෝද්‍යානය.',
                'description_en' => 'Angamedilla National Park offers wilderness safaris, river trails, and wildlife observation points within easy reach from Bakamuna.',
                'description_si' => 'බකමූණ නගරයට ආසන්නව පිහිටි අංගමැඩිල්ල වනෝද්‍යානය වනජීවී ලෝලීන්ට සහ ඡායාරූප ශිල්පීන්ට අගනා තෝතැන්නකි.',
                'category' => 'Nature & Wildlife',
                'category_si' => 'සොබාදහම සහ වනජීවී',
                'route_zone' => 'Zone A - Bakamuna Route',
                'distance_km' => 6.5,
                'travel_time_minutes' => 15,
                'stay_duration_minutes' => 120,
                'nearest_bus_stop' => 'Bakamuna Town Hub',
                'nearest_bus_stop_id' => $stopModels['BAKAMUNA']->id,
                'bus_stop_walk_minutes' => 15,
                'bus_route_numbers' => 'Route 422, Route 550, Route 422/2',
                'latitude' => 7.9400,
                'longitude' => 80.8600,
                'opening_time' => '06:00',
                'closing_time' => '18:00',
                'is_always_open' => false,
                'ticket_price_min' => 500,
                'ticket_price_max' => 1500,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title_en' => 'Bowatenna Dam & Viewpoint',
                'title_si' => 'බෝවතැන්න ජලාශය සහ නැරඹුම් මැදිරිය',
                'slug' => 'bowatenna-viewpoint',
                'short_description_en' => 'Historic hydro-power dam with misty mountain vistas along the Naula-Elahera mountain road.',
                'short_description_si' => 'නාවුල-ඇලහැර මාවතේ පිහිටි ජල විදුලි බලාගාරය සහ මීදුම් සළු පැළඳි කඳු වළල්ලේ මනරම් දසුන.',
                'description_en' => 'Bowatenna Dam diverts Mahaweli waters to the northern dry zone. The viewpoint offers panoramic photography opportunities.',
                'description_si' => 'නකල්ස් කඳු පාමුලින් ගලා එන ජල කඳ රඳවාගත් බෝවතැන්න ජලාශය සොබාදහම් සෞන්දර්යයෙන් අනූන ස්ථානයකි.',
                'category' => 'Viewpoints',
                'category_si' => 'නැරඹුම් ස්ථාන',
                'route_zone' => 'Zone B - Elahera & Moragahakanda Route',
                'distance_km' => 31.0,
                'travel_time_minutes' => 45,
                'stay_duration_minutes' => 45,
                'nearest_bus_stop' => 'Naula Town Junction',
                'nearest_bus_stop_id' => $stopModels['NAULA']->id,
                'bus_stop_walk_minutes' => 12,
                'bus_route_numbers' => 'Route 422, Route 45',
                'latitude' => 7.6700,
                'longitude' => 80.6800,
                'opening_time' => '06:00',
                'closing_time' => '18:00',
                'is_always_open' => true,
                'ticket_price_min' => 0,
                'ticket_price_max' => 0,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($destinationsData as $dest) {
            Destination::updateOrCreate(
                ['slug' => $dest['slug']],
                $dest
            );
        }
    }
}
