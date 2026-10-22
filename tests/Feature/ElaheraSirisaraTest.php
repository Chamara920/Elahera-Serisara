<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\User;
use Tests\TestCase;

class ElaheraSirisaraTest extends TestCase
{
    /**
     * Test Home Page loads successfully.
     */
    public function test_home_page_loads_with_brand_name(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Elahera Serisara');
        $response->assertSee('Smart Day-Trip Planner');
    }

    /**
     * Test Destinations page.
     */
    public function test_destinations_catalog_page(): void
    {
        $response = $this->get('/destinations');
        $response->assertStatus(200);
        $response->assertSee('Available Destinations');
    }

    /**
     * Test Single Destination view and Sinhala fallback notice.
     */
    public function test_single_destination_detail_page(): void
    {
        $destination = Destination::firstOrCreate(
            ['slug' => 'elahera-ancient-anicut'],
            [
                'title_en' => 'Elahera Ancient Anicut',
                'title_si' => 'ඇලහැර පුරාණ අමුණ',
                'short_description_en' => 'Built across Amban Ganga by King Mahasen.',
                'description_en' => 'The Elahera Anicut is a monumental stone dam.',
                'description_si' => 'ඇලහැර අමුණ යනු මහවැලි ගඟෙහි ප්‍රධාන අතු ගංගාවකි.',
                'category' => 'Historical & Cultural',
                'route_zone' => 'Zone B - Elahera Anicut',
                'latitude' => 7.9866,
                'longitude' => 80.8144,
                'is_active' => true,
            ]
        );

        $response = $this->get('/destinations/' . $destination->slug);
        $response->assertStatus(200);
        $response->assertSee('Elahera Ancient Anicut');
        $response->assertSee('Google Maps මඟින් යන්න');
    }

    /**
     * Test Sinhala fallback warning banner when destination lacks Sinhala translation.
     */
    public function test_sinhala_fallback_notice_displays_when_sinhala_missing(): void
    {
        Destination::firstOrCreate(
            ['slug' => 'moragahakanda-reservoir-dam-viewpoint'],
            [
                'title_en' => 'Moragahakanda Reservoir Dam Viewpoint',
                'title_si' => null, // Deliberately null to test fallback
                'short_description_en' => 'Panoramic vista over the largest reservoir.',
                'description_en' => 'Provides sweeping bird-eye perspectives.',
                'description_si' => null, // Deliberately null to test fallback
                'category' => 'Viewpoints',
                'route_zone' => 'Zone D - Moragahakanda Viewpoints',
                'latitude' => 7.7058,
                'longitude' => 80.7811,
                'is_active' => true,
            ]
        );

        // Switch locale to Sinhala
        $response = $this->withSession(['locale' => 'si'])->get('/destinations/moragahakanda-reservoir-dam-viewpoint');
        $response->assertStatus(200);
        // Expect fallback banner alert
        $response->assertSee('දැනුම්දීමයි:');
        $response->assertSee('මෙම තොරතුරු සිංහල බසින් තවමත් සකස් කර නොමැති බැවින් ඉංග්‍රීසි භාෂාවෙන් ඉදිරිපත් කෙරේ.');
        // Expect English title shown as fallback
        $response->assertSee('Moragahakanda Reservoir Dam Viewpoint');
    }

    /**
     * Test Smart Trip Planner page.
     */
    public function test_smart_trip_planner_page(): void
    {
        $response = $this->get('/trip-planner');
        $response->assertStatus(200);
        $response->assertSee('Live Day-Trip Timeline');
        $response->assertSee('Save Trip to My Account');
    }

    /**
     * Test Interactive GPS Map page.
     */
    public function test_interactive_map_page(): void
    {
        $response = $this->get('/map');
        $response->assertStatus(200);
        $response->assertSee('Interactive Map');
    }

    /**
     * Test Public Transport & Bus Timetable with disclaimer.
     */
    public function test_public_transport_page_and_disclaimer(): void
    {
        $response = $this->get('/public-transport');
        $response->assertStatus(200);
        $response->assertSee('Bakamuna Hub Master Bus Timetable');
        $response->assertSee('විශේෂ නිවේදන සටහන (Disclaimer Notice)');
    }

    /**
     * Test Community Blog page.
     */
    public function test_community_blog_page(): void
    {
        $response = $this->get('/blog');
        $response->assertStatus(200);
        $response->assertSee('Community Stories');
    }

    /**
     * Test Forum Q&A page.
     */
    public function test_forum_page(): void
    {
        $response = $this->get('/forum');
        $response->assertStatus(200);
        $response->assertSee('Traveler Discussion Forum');
    }

    /**
     * Test News and Events page.
     */
    public function test_news_and_events_page(): void
    {
        $response = $this->get('/news');
        $response->assertStatus(200);
        $response->assertSee('News, Notices', false);
    }

    /**
     * Test Emergency Directory.
     */
    public function test_emergency_directory_page(): void
    {
        $response = $this->get('/emergency');
        $response->assertStatus(200);
        $response->assertSee('Emergency Contacts', false);
        $response->assertSee('1990');
    }

    /**
     * Test About Page.
     */
    public function test_about_page(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('Elahera Serisara');
        $response->assertSee('Discover • Explore • Experience');
    }
}
