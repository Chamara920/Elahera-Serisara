<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\DestinationCategory;
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

        $response = $this->get('/destinations/'.$destination->slug);
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
     * Test Smart Trip Planner page, saving trip, and print view.
     */
    public function test_smart_trip_planner_page(): void
    {
        $response = $this->get('/trip-planner');
        $response->assertStatus(200);
        $response->assertSee('Live Day-Trip Timeline');
        $response->assertSee('Save Trip to My Account');

        // Unauthenticated save returns 401 with login redirect
        $saveGuest = $this->postJson('/trip-planner/save', [
            'title' => 'My Elahera Trip',
            'start_location' => 'Bakamuna',
            'start_time' => '08:00',
            'end_time' => '18:00',
            'transport_mode' => 'Car/Van',
            'meal_break' => true,
            'stops' => [['id' => 1, 'title' => 'Elahera Anicut']],
            'total_duration_minutes' => 600,
        ]);
        $saveGuest->assertStatus(401);

        // Authenticated user can save trip
        $user = User::factory()->create();
        $saveAuth = $this->actingAs($user)->postJson('/trip-planner/save', [
            'title' => 'My Elahera Trip',
            'start_location' => 'Bakamuna',
            'start_time' => '08:00',
            'end_time' => '18:00',
            'transport_mode' => 'Car/Van',
            'meal_break' => true,
            'stops' => [['id' => 1, 'title' => 'Elahera Anicut']],
            'total_duration_minutes' => 360,
        ]);
        $saveAuth->assertStatus(200);
        $saveAuth->assertJson(['success' => true]);

        // Printable itinerary view
        $printRes = $this->get('/trip-planner/print?stops='.urlencode(json_encode([['id' => 1, 'title' => 'Elahera Anicut']])));
        $printRes->assertStatus(200);
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

    /**
     * Test Planner Bilingual Localization on Home and Planner pages.
     */
    public function test_planner_localization_bilingual(): void
    {
        // In English: should see English planner subtitle
        $enResponse = $this->withSession(['locale' => 'en'])->get('/');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('Plan your journey seamlessly using verified destinations');

        // In Sinhala: should see Sinhala planner subtitle
        $siResponse = $this->withSession(['locale' => 'si'])->get('/');
        $siResponse->assertStatus(200);
        $siResponse->assertSee('යෙදවුමේ ඇති ගමනාන්ත, රැඳෙන කාලය, ගමන් කාලය සහ පොදු ප්‍රවාහන දත්ත ඇසුරෙන්ම ඔබේ සංචාරය සැලසුම් කරන්න.');

        // Planner Index page in English
        $enPlanner = $this->withSession(['locale' => 'en'])->get('/trip-planner');
        $enPlanner->assertStatus(200);
        $enPlanner->assertSee('Database-Driven Transit Planning');

        // Planner Index page in Sinhala
        $siPlanner = $this->withSession(['locale' => 'si'])->get('/trip-planner');
        $siPlanner->assertStatus(200);
        $siPlanner->assertSee('දත්ත පදනම් කරගත් ප්‍රවාහන සැලසුම්කරණය');
    }

    /**
     * Test Destination image helpers and fallback.
     */
    public function test_destination_image_resolution(): void
    {
        $dest = new Destination([
            'title_en' => 'Test Destination',
            'featured_image' => null,
            'gallery_images' => null,
        ]);
        $this->assertStringContainsString('anicut.svg', $dest->getFeaturedImageUrl());
        $this->assertEmpty($dest->getGalleryImageUrls());

        $dest->gallery_images = ['destinations/test1.jpg'];
        $gallery = $dest->getGalleryImageUrls();
        $this->assertCount(1, $gallery);
    }

    /**
     * Test Category auto-sync on Destination save.
     */
    public function test_destination_category_auto_sync(): void
    {
        $cat = DestinationCategory::firstOrCreate(
            ['slug' => 'test-nature-category'],
            ['name_en' => 'Test Nature', 'name_si' => 'පරීක්ෂණ සොබාදහම', 'is_active' => true]
        );

        $destination = new Destination([
            'title_en' => 'Auto Sync Destination',
            'slug' => 'auto-sync-dest-'.uniqid(),
            'short_description_en' => 'Testing auto sync',
            'description_en' => 'Testing auto sync description',
            'category_id' => $cat->id,
            'route_zone' => 'Zone A - Bakamuna Route',
            'distance_km' => 5,
            'travel_time_minutes' => 10,
            'stay_duration_minutes' => 30,
            'latitude' => 7.9,
            'longitude' => 80.8,
            'is_active' => true,
        ]);
        $destination->save();

        $this->assertEquals('Test Nature', $destination->category);
        $this->assertEquals('පරීක්ෂණ සොබාදහම', $destination->category_si);
    }

    /**
     * Test Admin resource create pages load with RichEditor for admin user.
     */
    public function test_admin_create_pages_render_rich_editor(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@elaherasirisara.lk'],
            [
                'name' => 'System Admin',
                'password' => bcrypt('Admin@12345'),
                'role' => 'admin',
            ]
        );

        $this->actingAs($admin)
            ->get('/admin/destinations/create')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get('/admin/blog-posts/create')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get('/admin/news-events/create')
            ->assertStatus(200);
    }

    /**
     * Test Register page renders localized strings based on user selected language.
     */
    public function test_register_page_bilingual_localization(): void
    {
        // 1. English
        $enResponse = $this->withSession(['locale' => 'en'])->get('/register');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('Create Community Account');
        $enResponse->assertSee('Full Name');
        $enResponse->assertSee('Email Address');
        $enResponse->assertSee('Register as Member');
        $enResponse->assertSee('What is your primary school name?');

        // 2. Sinhala
        $siResponse = $this->withSession(['locale' => 'si'])->get('/register');
        $siResponse->assertStatus(200);
        $siResponse->assertSee('ප්‍රජා ගිණුමක් සාදන්න');
        $siResponse->assertSee('සම්පූර්ණ නම');
        $siResponse->assertSee('විද්‍යුත් තැපැල් ලිපිනය');
        $siResponse->assertSee('සාමාජිකයෙකු ලෙස ලියාපදිංචි වන්න');
        $siResponse->assertSee('ඔබේ ප්‍රාථමික පාසලේ නම කුමක්ද?');
    }

    /**
     * Test locale switching strictly supports English and Sinhala, ignoring unsupported locales like Tamil.
     */
    public function test_locale_switching_and_unsupported_locale_handling(): void
    {
        // 1. Switch locale to Sinhala
        $switchResponseSi = $this->get('/locale/si');
        $switchResponseSi->assertRedirect();
        $this->assertEquals('si', session('locale'));

        // 2. Attempt switching to unsupported locale (ta) should be rejected
        $switchResponseTa = $this->get('/locale/ta');
        $switchResponseTa->assertRedirect();
        // Locale remains 'si' because 'ta' is not in allowed list ['en', 'si']
        $this->assertEquals('si', session('locale'));

        // 3. Switch back to English
        $switchResponseEn = $this->get('/locale/en');
        $switchResponseEn->assertRedirect();
        $this->assertEquals('en', session('locale'));
    }

    /**
     * Test Contact Form in footer validates properly and saves message.
     */
    public function test_footer_contact_form_submission_and_validation(): void
    {
        // 1. Validation failure: missing message
        $invalid = $this->post('/contact', [
            'name' => 'Kusal Mendis',
            'email' => 'kusal@example.com',
            'message' => 'short', // less than 10 characters
        ]);
        $invalid->assertSessionHasErrors(['message']);

        // 2. Valid submission
        $valid = $this->post('/contact', [
            'name' => 'Kusal Mendis',
            'email' => 'kusal@example.com',
            'message' => 'Hello, I would like to learn more about the Elahera canal boat tour.',
        ]);
        $valid->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'kusal@example.com',
        ]);
    }
}
