<?php

namespace App\Http\Requests\Api\V1\Payment;

use App\Http\Requests\BaseRequest;
use App\Models\Order;
use Illuminate\Validation\Rule;

class PaymentRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'integer',
                Rule::exists(Order::class, 'id'),
            ],
            'payment_method' => 'required|string|in:stripe,paypal',
            'amount' => 'required|numeric|min:0.01',
        ];
    }
}
