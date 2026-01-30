<?php

namespace App\DTOs\V1\Payment;

use Illuminate\Http\Request;

readonly class PaymentDto
{
    public function __construct(
        public int $order_id,
        public string $payment_method,
        public float $amount,
        public ?string $status = null,
        public ?string $transaction_id = null
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            order_id: (int) $request->input('order_id'),
            payment_method: $request->input('payment_method'),
            amount: (float) $request->input('amount')
        );
    }

    public function withResult(string $status, string $transaction_id): self
    {
        return new self(
            order_id: $this->order_id,
            payment_method: $this->payment_method,
            amount: $this->amount,
            status: $status,
            transaction_id: $transaction_id
        );
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->order_id,
            'payment_method' => $this->payment_method,
            'amount' => $this->amount,
            'status' => $this->status,
            'transaction_id' => $this->transaction_id,
        ];
    }
}
