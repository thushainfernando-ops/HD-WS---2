<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_api_requires_authentication(): void
    {
        $this->getJson('/api/customer/cart')
            ->assertUnauthorized();
    }

    public function test_authenticated_customer_can_fetch_cart(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $item = MenuItem::create([
            'name' => 'Kottu Roti',
            'description' => 'Street-style kottu',
            'price' => 850,
            'category' => 'Main Course',
            'image' => 'assets/menu/kottu.jpg',
        ]);

        CartItem::create([
            'user_id' => $user->id,
            'item_id' => $item->item_id,
            'quantity' => 2,
        ]);

        $token = $user->createToken('api-test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/customer/cart')
            ->assertOk()
            ->assertJsonPath('data.0.quantity', 2)
            ->assertJsonPath('data.0.item.name', 'Kottu Roti');
    }

    public function test_admin_can_fetch_dashboard_summary(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        MenuItem::create([
            'name' => 'Fish Curry',
            'description' => 'Traditional fish curry',
            'price' => 950,
            'category' => 'Lunch',
            'image' => 'assets/menu/fish.jpg',
        ]);

        Order::create([
            'user_id' => $user->id,
            'total_amount' => 950,
            'status' => 'Pending',
        ]);

        $token = $admin->createToken('admin-api')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/admin/summary')
            ->assertOk()
            ->assertJsonPath('data.users', 2)
            ->assertJsonPath('data.orders', 1)
            ->assertJsonPath('data.menu_items', 1);
    }

    public function test_non_admin_cannot_fetch_admin_summary(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $token = $user->createToken('customer-api')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/admin/summary')
            ->assertForbidden();
    }
}
