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
     * Test administrator can access Filament dashboard.
     */
    public function test_admin_user_can_access_filament_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Destinations');
        $response->assertSee('Hero Sliders');
        $response->assertSee('Bus Routes');
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
