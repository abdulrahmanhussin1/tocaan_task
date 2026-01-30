<?php

namespace App\Http\Requests\Api\V1\Order;

use App\Http\Requests\BaseRequest;
use App\Enums\StatusEnum;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'string', Rule::in(StatusEnum::getOrderStatuses())],
            'items' => 'sometimes|array|min:1',
            'items.*.product_name' => 'required_with:items|string|max:255',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.price' => 'required_with:items|numeric|min:0',
        ];
    }
}
