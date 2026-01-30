<?php

namespace App\DTOs\V1\Order;

use Illuminate\Http\Request;

readonly class OrderDto
{
    public function __construct(
        public int $user_id,
        public array $items
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            user_id: auth()->id(),
            items: $request->input('items')
        );
    }
}
