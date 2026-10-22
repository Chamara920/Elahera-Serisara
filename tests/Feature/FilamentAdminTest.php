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
