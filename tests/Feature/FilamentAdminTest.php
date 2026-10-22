<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class FilamentAdminTest extends TestCase
{
    /**
     * Test admin login page is accessible.
     */
    public function test_admin_login_page_loads(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    /**
     * Test administrator can access Filament dashboard and sees categorized menus in English.
     */
    public function test_admin_user_can_access_filament_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Elahera Serisara Admin');
        $response->assertSee('Home Page');
        $response->assertSee('Hero Sliders');
        $response->assertSee('Homepage Destinations');
        $response->assertSee('Destination Categories');
        $response->assertSee('Destinations &amp; Transport', false);
        $response->assertSee('Database');
        $response->assertSee('Backup &amp; Restore', false);
        $response->assertSee('Blog Comments');
        $response->assertSee('News &amp; Event Comments', false);
    }

    /**
     * Test admin can access blog comments resource in Filament.
     */
    public function test_admin_can_access_blog_comments_resource(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/blog-comments');
        $response->assertStatus(200);
    }

    /**
     * Test admin can access news event comments resource in Filament.
     */
    public function test_admin_can_access_news_event_comments_resource(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/news-event-comments');
        $response->assertStatus(200);
    }

    /**
     * Test admin can access homepage destinations management page in English.
     */
    public function test_admin_can_access_homepage_destinations_page(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/homepage-destinations');
        $response->assertStatus(200);
        $response->assertSee('Featured Homepage Destinations');
    }

    /**
     * Test admin can access database backup and restore page in English.
     */
    public function test_admin_can_access_database_backup_page(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/database-backup-restore');
        $response->assertStatus(200);
        $response->assertSee('Create Full Database Backup');
    }

    /**
     * Test admin can access destination categories resource.
     */
    public function test_admin_can_access_destination_categories_page(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/destination-categories');
        $response->assertStatus(200);
    }

    /**
     * Test admin can access destination creation page with operational hours and seasonal sections in English.
     */
    public function test_admin_can_access_destinations_create_page(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/destinations/create');
        $response->assertStatus(200);
        $response->assertSee('Visiting Hours &amp; Weekly Schedule', false);
        $response->assertSee('12-Month Seasonal Suitability', false);
        $response->assertSee('Ticket Pricing &amp; Entrance Fees', false);
        $response->assertSee('Opening Time');
        $response->assertSee('Closing Time');
        $response->assertSee('Closed Days / Weekly Holidays', false);
    }

    /**
     * Test destination model operational hours, closed days, and seasonal calculations.
     */
    public function test_destination_operational_and_seasonal_attributes(): void
    {
        $destination = \App\Models\Destination::create([
            'title_en' => 'Wasgamuwa Park Corridor',
            'slug' => 'wasgamuwa-park-corridor',
            'short_description_en' => 'Wildlife reserve corridor near Elahera.',
            'description_en' => 'Scenic elephant watching and river biodiversity.',
            'category' => 'Nature & Wildlife',
            'route_zone' => 'Zone C - Wildlife & Angamedilla',
            'distance_km' => 18,
            'travel_time_minutes' => 30,
            'stay_duration_minutes' => 120,
            'opening_time' => '06:00',
            'closing_time' => '18:00',
            'is_always_open' => false,
            'closed_days' => ['Monday', 'Full Moon Poya Days'],
            'closure_notes' => 'Closed on Poya days for wildlife sanctuary rest',
            'best_months' => ['July', 'August', 'September'],
            'moderate_months' => ['January', 'February', 'March'],
            'not_recommended_months' => ['November', 'December'],
            'ticket_price_min' => 150,
            'ticket_price_max' => 600,
            'ticket_price_range' => 'LKR 150 - LKR 600',
            'latitude' => 7.9123,
            'longitude' => 80.8954,
            'is_active' => true,
        ]);

        $this->assertEquals('06:00 - 18:00', $destination->getFormattedOpeningHours());
        $this->assertTrue($destination->isOpenAt('10:00'));
        $this->assertFalse($destination->isOpenAt('05:30'));
        $this->assertFalse($destination->isOpenAt('19:00'));

        $this->assertTrue($destination->isClosedOnDay('Monday'));
        $this->assertFalse($destination->isClosedOnDay('Tuesday'));

        $this->assertEquals('best', $destination->getSeasonalStatusForMonth('August'));
        $this->assertEquals('not_recommended', $destination->getSeasonalStatusForMonth('November'));
        $this->assertEquals('moderate', $destination->getSeasonalStatusForMonth('February'));
        $this->assertEquals('normal', $destination->getSeasonalStatusForMonth('April'));

        $this->assertEquals('LKR 150 - LKR 600', $destination->getFormattedTicketPrice());
    }

    /**
     * Test community member cannot access Filament admin panel.
     */
    public function test_community_member_forbidden_from_admin_panel(): void
    {
        $member = User::firstOrCreate(
            ['email' => 'test_community_member@elaheraserisara.lk'],
            [
                'name' => 'Community Member',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'community_member',
            ]
        );
        $this->assertNotNull($member);

        $response = $this->actingAs($member)->get('/admin');
        // Filament returns 403 Forbidden for unauthorized users
        $response->assertStatus(403);
    }
}
