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
     * Test administrator can access Filament dashboard and sees categorized menus.
     */
    public function test_admin_user_can_access_filament_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('hero slider');
        $response->assertSee('මුල් පිටුව');
        $response->assertSee('මුල් පිටුවේ දර්ශනය විය යුතු සංචාරක ගමනාන්ත');
        $response->assertSee('සංචාරක ගමනාන්ත Category එකතු කිරීමට');
        $response->assertSee('Backup සහ Restore');
    }

    /**
     * Test admin can access homepage destinations management page.
     */
    public function test_admin_can_access_homepage_destinations_page(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/homepage-destinations');
        $response->assertStatus(200);
        $response->assertSee('මුල් පිටුවේ දර්ශනය වන සංචාරක ගමනාන්ත');
    }

    /**
     * Test admin can access database backup and restore page.
     */
    public function test_admin_can_access_database_backup_page(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/database-backup-restore');
        $response->assertStatus(200);
        $response->assertSee('Generate Backup Now');
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
        $member = User::where('role', 'community_member')->first();
        $this->assertNotNull($member);

        $response = $this->actingAs($member)->get('/admin');
        // Filament returns 403 Forbidden for unauthorized users
        $response->assertStatus(403);
    }
}
