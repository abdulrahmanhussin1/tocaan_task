<?php

namespace App\Services\V1\Payment;

use App\Exceptions\General\NotFoundItemException;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\V1\PaymentRepository;
use App\Repositories\V1\OrderRepository;
use App\Services\V1\Payment\Gateways\PaymentGatewayInterface;
use App\Services\V1\Payment\Gateways\StripeGateway;
use App\Services\V1\Payment\Gateways\PayPalGateway;
use App\DTOs\V1\Payment\PaymentDto;
use App\Enums\StatusEnum;
use App\Exceptions\General\LogicalException;
use Illuminate\Support\Collection;

readonly class PaymentService
{
    public function __construct(
        private PaymentRepository $paymentRepository,
        private OrderRepository   $orderRepository
    ) {
    }

    /**
     * Process a payment for an order.
     * @throws LogicalException|NotFoundItemException
     */
    public function processPayment(PaymentDto $dto): Payment
    {
        $order = $this->orderRepository->find($dto->order_id);

        if ($order->status !== StatusEnum::CONFIRMED->value) {
            throw new LogicalException("Payments can only be processed for orders in confirmed status.");
        }

        $gateway = $this->resolveGateway($dto->payment_method);
        $result = $gateway->process($order, $dto->amount);

        $paymentDto = $dto->withResult($result['status'], $result['transaction_id']);

        return $this->paymentRepository->create($paymentDto);
    }

    public function getAllPayments(): Collection
    {
        return $this->paymentRepository->all();
    }

    public function getOrderPayments(int $orderId): Collection
    {
        return $this->paymentRepository->get(['order_id' => $orderId]);
    }

    /**
     * @throws LogicalException
     */
    private function resolveGateway(string $method): PaymentGatewayInterface
    {
        return match ($method) {
            'stripe' => new StripeGateway(),
            'paypal' => new PayPalGateway(),
            default => throw new LogicalException("Payment method {$method} is not supported."),
        };
    }
}
