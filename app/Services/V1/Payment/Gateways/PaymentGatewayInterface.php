<?php

namespace App\Services\V1\Payment\Gateways;

use App\Models\Order;

interface PaymentGatewayInterface
{
    public function process(Order $order, float $amount): array;
}
