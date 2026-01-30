<?php

namespace App\Services\V1\Payment\Gateways;

use App\Models\Order;
use App\Enums\StatusEnum;

class PayPalGateway implements PaymentGatewayInterface
{
    private string $clientId;
    private string $secret;

    public function __construct()
    {
        $this->clientId = config('payments.paypal.client_id') ?? 'default_mock_client_id';
        $this->secret = config('payments.paypal.secret') ?? 'default_mock_secret';
    }

    public function process(Order $order, float $amount): array
    {
        // Use $this->clientId and $this->secret to communicate with PayPal API
        return [
            'status' => StatusEnum::SUCCESSFUL->value,
            'transaction_id' => 'pp_' . uniqid('', true),
        ];
    }
}
