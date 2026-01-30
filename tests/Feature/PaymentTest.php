<?php

namespace Tests\Feature;

use App\Enums\HTTPStatusCodeEnum;
use App\Models\User;
use App\Models\Order;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentTest extends TestCase
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

    public function test_can_process_payment_for_confirmed_order(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'total_price' => 100,
            'status' => StatusEnum::CONFIRMED->value
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/payments/process', [
                'order_id' => $order->id,
                'payment_method' => 'stripe',
                'amount' => 100,
            ]);

        $response->assertStatus(HttpStatusCodeEnum::OK->value)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', StatusEnum::SUCCESSFUL->value);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'amount' => 100,
            'status' => StatusEnum::SUCCESSFUL->value
        ]);
    }

    public function test_cannot_process_payment_for_pending_order()
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'total_price' => 100,
            'status' => StatusEnum::PENDING->value
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/payments/process', [
                'order_id' => $order->id,
                'payment_method' => 'paypal',
                'amount' => 100,
            ]);

        $response->assertStatus(HTTPStatusCodeEnum::BAD_REQUEST->value);
    }

    public function test_can_retrieve_order_payments(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'total_price' => 100,
            'status' => StatusEnum::CONFIRMED->value
        ]);
        $order->payments()->create([
            'payment_method' => 'stripe',
            'amount' => 100,
            'status' => StatusEnum::SUCCESSFUL->value,
            'transaction_id' => 'st_123'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("/api/v1/payments/order/{$order->id}");

        $response->assertStatus(HTTPStatusCodeEnum::OK->value)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_retrieve_all_payments(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'total_price' => 100,
            'status' => StatusEnum::CONFIRMED->value
        ]);
        $order->payments()->create([
            'payment_method' => 'stripe',
            'amount' => 100,
            'status' => StatusEnum::SUCCESSFUL->value,
            'transaction_id' => 'st_123'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/payments');

        $response->assertStatus(HTTPStatusCodeEnum::OK->value)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data']);
    }

    public function test_process_payment_returns_401_without_token(): void
    {
        $response = $this->postJson('/api/v1/payments/process', [
            'order_id' => 1,
            'payment_method' => 'stripe',
            'amount' => 100,
        ]);

        $response->assertStatus(HTTPStatusCodeEnum::UNAUTHORIZED->value);
    }
}
