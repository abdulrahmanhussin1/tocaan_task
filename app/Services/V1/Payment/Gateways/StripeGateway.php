<?php

namespace App\Services\V1\Payment\Gateways;

use App\Enums\StatusEnum;
use App\Models\Order;

class StripeGateway implements PaymentGatewayInterface
{
    private string $key;

    private string $secret;

    public function __construct()
    {
        $this->key = config('payments.stripe.key') ?? 'default_mock_key';
        $this->secret = config('payments.stripe.secret') ?? 'default_mock_secret';
    }

    public function process(Order $order, float $amount): array
    {
        // Use $this->secret to communicate with Stripe API
        return [
            'status' => StatusEnum::SUCCESSFUL->value,
            'transaction_id' => 'st_'.uniqid('', true),
        ];
    }
}
