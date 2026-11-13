<?php

namespace Tests\Feature;

use App\Models\BusStop;
use App\Models\Destination;
use Database\Seeders\SmartTransitSeeder;
use Tests\TestCase;

class SmartTransitPlannerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SmartTransitSeeder::class);
    }

    /**
     * Test Trip Planner page renders successfully with bus stops and default hub.
     */
    public function test_planner_page_renders_with_transit_stop_selector(): void
    {
        $response = $this->get('/trip-planner');

        $response->assertStatus(200);
        $response->assertSee('Smart Day-Trip Planner');
        $response->assertSee('Public Bus');
        $response->assertSee('Bakamuna Town Hub');
    }

    /**
     * Test bus stops JSON endpoint returns all active bus stops.
     */
    public function test_bus_stops_api_endpoint(): void
    {
        $response = $this->getJson('/trip-planner/bus-stops');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'stops' => [
                '*' => [
                    'id',
                    'name_en',
                    'name_si',
                    'code',
                    'latitude',
                    'longitude',
                    'is_hub',
                ],
            ],
        ]);
    }

    /**
     * Test calculating transit route for multi-destination journey with direct & transfer legs.
     */
    public function test_calculate_transit_route_returns_itinerary_and_alternatives(): void
    {
        $bakamuna = BusStop::where('code', 'BAKAMUNA')->first();
        $this->assertNotNull($bakamuna, 'Bakamuna bus stop must exist.');

        $destinations = Destination::whereIn('slug', ['moragahakanda-reservoir', 'kumara-ella-falls'])->get();

        if ($destinations->count() < 2) {
            $destinations = Destination::take(2)->get();
        }

        $payload = [
            'start_stop_id' => $bakamuna->id,
            'start_time' => '08:00',
            'service_day' => 'all',
            'stops' => $destinations->map(function ($dest) {
                return [
                    'destination_id' => $dest->id,
                    'stay_minutes' => 60,
                ];
            })->toArray(),
            'chosen_alternatives' => [],
        ];

        $response = $this->postJson('/trip-planner/calculate-transit', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'plan' => [
                'start_stop',
                'start_time',
                'finish_time',
                'total_used_minutes',
                'total_travel_minutes',
                'total_waiting_minutes',
                'total_stay_minutes',
                'total_transfers_count',
                'has_missed_connections',
                'legs' => [
                    '*' => [
                        'leg_index',
                        'from_stop_name',
                        'destination_title',
                        'to_stop_name',
                        'stay_minutes',
                        'available_alternatives',
                        'connection',
                    ],
                ],
                'return_leg',
            ],
        ]);
    }

    /**
     * Test optimizing destination sequence for public bus transit schedule.
     */
    public function test_optimize_transit_sequence_endpoint(): void
    {
        $bakamuna = BusStop::where('code', 'BAKAMUNA')->first();
        $this->assertNotNull($bakamuna);

        $destinations = Destination::take(3)->get();

        $payload = [
            'start_stop_id' => $bakamuna->id,
            'start_time' => '08:00',
            'service_day' => 'all',
            'stops' => $destinations->map(function ($dest) {
                return [
                    'destination_id' => $dest->id,
                    'stay_minutes' => 60,
                ];
            })->toArray(),
        ];

        $response = $this->postJson('/trip-planner/optimize-transit-sequence', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'optimization' => [
                'optimized_stops',
                'time_saved_minutes',
                'itinerary',
                'is_reordered',
            ],
        ]);
    }
}
