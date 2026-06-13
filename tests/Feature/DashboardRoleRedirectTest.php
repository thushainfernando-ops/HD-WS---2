<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRoleRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_users_redirect_to_the_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_non_admin_users_redirect_to_the_customer_profile(): void
    {
        $customer = User::factory()->create([
            'role' => 'user',
        ]);

        $this->actingAs($customer)
            ->get('/dashboard')
            ->assertRedirect(route('profile.show'));
    }
}
