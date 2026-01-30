<?php

namespace Tests\Feature;

use App\Enums\HTTPStatusCodeEnum;
use App\Models\User;
use App\Models\Order;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->token = JWTAuth::fromUser($this->user);
    }

    public function test_can_create_order(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/orders', [
                'items' => [
                    ['product_name' => 'Product 1', 'quantity' => 2, 'price' => 100],
                    ['product_name' => 'Product 2', 'quantity' => 1, 'price' => 50],
                ],
            ]);

        $response->assertStatus(HttpStatusCodeEnum::OK->value);

        $this->assertDatabaseHas('orders', ['user_id' => $this->user->id, 'total_price' => 250]);
    }

    public function test_can_list_orders()
    {
        Order::create(['user_id' => $this->user->id, 'total_price' => 100, 'status' => StatusEnum::PENDING->value]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/orders');

        $response->assertStatus(HTTPStatusCodeEnum::OK->value);
    }

    public function test_can_update_order(): void
    {
        $order = Order::create(['user_id' => $this->user->id, 'total_price' => 100, 'status' => StatusEnum::PENDING->value]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/v1/orders/{$order->id}", [
                'status' => StatusEnum::CONFIRMED->value,
            ]);

        $response->assertStatus(HTTPStatusCodeEnum::OK->value);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => StatusEnum::CONFIRMED->value]);
    }

    public function test_cannot_delete_order_with_payments(): void
    {
        $order = Order::create(['user_id' => $this->user->id, 'total_price' => 100, 'status' => StatusEnum::CONFIRMED->value]);
        $order->payments()->create([
            'payment_method' => 'stripe',
            'amount' => 100,
            'status' => StatusEnum::SUCCESSFUL->value,
            'transaction_id' => 'abc',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("/api/v1/orders/{$order->id}");

        $response->assertStatus(HTTPStatusCodeEnum::BAD_REQUEST->value);
    }

    public function test_can_delete_order_without_payments(): void
    {
        $order = Order::create(['user_id' => $this->user->id, 'total_price' => 100, 'status' => StatusEnum::PENDING->value]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("/api/v1/orders/{$order->id}");

        $response->assertStatus(HTTPStatusCodeEnum::OK->value)
            ->assertJsonPath('success', true);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_can_show_single_order(): void
    {
        $order = Order::create(['user_id' => $this->user->id, 'total_price' => 100, 'status' => StatusEnum::PENDING->value]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("/api/v1/orders/{$order->id}");

        $response->assertStatus(HTTPStatusCodeEnum::OK->value)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.total_price', 100);
    }
}
