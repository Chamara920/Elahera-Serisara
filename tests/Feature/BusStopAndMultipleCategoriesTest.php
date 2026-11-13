<?php

namespace Tests\Feature;

use App\Models\BusRoute;
use App\Models\BusRouteHalt;
use App\Models\BusStop;
use App\Models\Destination;
use App\Models\DestinationCategory;
use Tests\TestCase;

class BusStopAndMultipleCategoriesTest extends TestCase
{
    /**
     * Test bus stop creation with GPS coordinates, connecting routes, and transfer hub flag.
     */
    public function test_bus_stop_can_be_created_with_gps_and_connecting_routes(): void
    {
        $stop = BusStop::create([
            'name_en' => 'Test Junction Hub',
            'name_si' => 'පරීක්ෂණ හන්දිය',
            'code' => 'TEST_HUB',
            'connecting_routes' => 'Route 422, Route 45/1',
            'latitude' => 7.8500,
            'longitude' => 80.7900,
            'is_hub' => true,
            'location_description_en' => 'Main connecting junction',
        ]);

        $this->assertDatabaseHas('bus_stops', [
            'code' => 'TEST_HUB',
            'is_hub' => true,
            'connecting_routes' => 'Route 422, Route 45/1',
        ]);

        $this->assertEquals('Test Junction Hub', $stop->getLocalizedName());
    }

    /**
     * Test destination can have primary category and multiple additional categories.
     */
    public function test_destination_supports_multiple_categories(): void
    {
        $cat1 = DestinationCategory::create([
            'name_en' => 'Waterfalls',
            'name_si' => 'දියඇලි',
            'slug' => 'test-waterfalls',
        ]);

        $cat2 = DestinationCategory::create([
            'name_en' => 'Nature & Wildlife',
            'name_si' => 'සොබාදහම සහ වනජීවී',
            'slug' => 'test-nature-wildlife',
        ]);

        $destination = Destination::create([
            'title_en' => 'Samanala Waterfall Test',
            'slug' => 'samanala-waterfall-test',
            'short_description_en' => 'A beautiful cascading waterfall.',
            'description_en' => 'Detailed description of the waterfall and forest.',
            'category_id' => $cat1->id,
            'category' => $cat1->name_en,
            'route_zone' => 'Zone A - Bakamuna Route',
        ]);

        // Attach secondary/additional categories
        $destination->categories()->attach([$cat1->id, $cat2->id]);

        $this->assertCount(2, $destination->fresh()->categories);
        $this->assertTrue($destination->categories->contains($cat1));
        $this->assertTrue($destination->categories->contains($cat2));

        $localizedNames = $destination->getAllLocalizedCategories();
        $this->assertContains('Waterfalls', $localizedNames);
        $this->assertContains('Nature & Wildlife', $localizedNames);
    }

    /**
     * Test destination filtering matches secondary category.
     */
    public function test_destinations_index_filters_by_secondary_category(): void
    {
        $catWater = DestinationCategory::create([
            'name_en' => 'Waterfalls',
            'name_si' => 'දියඇලි',
            'slug' => 'filter-waterfalls',
        ]);

        $catScenic = DestinationCategory::create([
            'name_en' => 'Scenic Viewpoints',
            'name_si' => 'දසුන් නැරඹුම් ස්ථාන',
            'slug' => 'filter-scenic',
        ]);

        $dest = Destination::create([
            'title_en' => 'Forest Falls',
            'slug' => 'forest-falls',
            'short_description_en' => 'A waterfall with great viewpoint.',
            'description_en' => 'Deep description.',
            'category_id' => $catWater->id,
            'category' => $catWater->name_en,
            'route_zone' => 'Zone B - Elahera Anicut',
            'is_active' => true,
        ]);

        $dest->categories()->attach([$catWater->id, $catScenic->id]);

        // Query by secondary category 'Scenic Viewpoints'
        $response = $this->get('/destinations?category=Scenic+Viewpoints');
        $response->assertStatus(200);
        $response->assertSee('Forest Falls');
    }

    /**
     * Test bus route halts and destination can link to bus stops.
     */
    public function test_bus_route_halt_and_destination_link_to_bus_stop(): void
    {
        $stop = BusStop::create([
            'name_en' => 'Elahera Canal Halt',
            'code' => 'ELAHERA_CANAL',
            'connecting_routes' => 'Route 422',
            'latitude' => 7.7470,
            'longitude' => 80.7780,
            'is_hub' => false,
        ]);

        $route = BusRoute::create([
            'route_number' => '422',
            'origin' => 'Matale',
            'destination' => 'Bakamuna',
            'service_type' => 'Normal',
            'origin_departure_time' => '06:00 AM',
            'is_active' => true,
        ]);

        $halt = BusRouteHalt::create([
            'bus_route_id' => $route->id,
            'bus_stop_id' => $stop->id,
            'stop_name' => $stop->name_en,
            'arrival_time' => '07:30 AM',
            'departure_time' => '07:35 AM',
            'stop_order' => 1,
        ]);

        $this->assertEquals($stop->id, $halt->fresh()->busStop->id);

        $dest = Destination::create([
            'title_en' => 'Elahera Ancient Canal',
            'slug' => 'elahera-ancient-canal',
            'short_description_en' => 'Historic stone canal.',
            'description_en' => 'Full history.',
            'category' => 'Historical & Cultural',
            'nearest_bus_stop_id' => $stop->id,
            'nearest_bus_stop' => $stop->name_en,
            'bus_stop_walk_minutes' => 3,
            'route_zone' => 'Zone B - Elahera Anicut',
            'is_active' => true,
        ]);

        $this->assertEquals($stop->id, $dest->fresh()->nearestBusStop->id);
        $this->assertEquals(3, $dest->fresh()->bus_stop_walk_minutes);
    }
}
