<?php

namespace Tests\Unit;

use App\Enums\StatusEnum;
use App\Models\Order;
use App\Models\User;
use App\Services\V1\Payment\Gateways\PayPalGateway;
use App\Services\V1\Payment\Gateways\StripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentGatewayTest extends TestCase
{
    use RefreshDatabase;

    public function test_stripe_gateway_returns_successful_status_and_transaction_id(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 100,
            'status' => StatusEnum::CONFIRMED->value,
        ]);

        $gateway = new StripeGateway();
        $result = $gateway->process($order, 100.00);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('transaction_id', $result);
        $this->assertSame(StatusEnum::SUCCESSFUL->value, $result['status']);
        $this->assertStringStartsWith('st_', $result['transaction_id']);
    }

    public function test_paypal_gateway_returns_successful_status_and_transaction_id(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
        ]);
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 100,
            'status' => StatusEnum::CONFIRMED->value,
        ]);

        $gateway = new PayPalGateway();
        $result = $gateway->process($order, 100.00);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('transaction_id', $result);
        $this->assertSame(StatusEnum::SUCCESSFUL->value, $result['status']);
        $this->assertStringStartsWith('pp_', $result['transaction_id']);
    }
}
