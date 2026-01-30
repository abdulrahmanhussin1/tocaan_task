<?php

namespace App\Services\V1;

use App\DTOs\FilterDto;
use App\DTOs\V1\Order\OrderDto;
use App\Enums\StatusEnum;
use App\Exceptions\General\LogicalException;
use App\Exceptions\General\NotFoundItemException;
use App\Models\Order;
use App\Repositories\V1\OrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {}

    /**
     * @throws Throwable
     */
    public function createOrder(OrderDto $dto): Order
    {
        return DB::transaction(function () use ($dto) {
            $total = 0;
            foreach ($dto->items as $item) {
                $total += $item['quantity'] * $item['price'];
            }
            // Preparing data for repository
            $orderData = [
                'user_id' => $dto->user_id,
                'total_price' => $total,
                'status' => StatusEnum::PENDING->value,
            ];

            $order = $this->orderRepository->create($orderData);

            foreach ($dto->items as $item) {
                $order->items()->create([
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return $order->load('items');
        });
    }

    /**
     * @throws Throwable
     */
    public function updateOrder(int $id, array $data): Order
    {
        return DB::transaction(function () use ($id, $data) {
            $order = $this->orderRepository->find($id);

            if (isset($data['items'])) {
                $order->items()->delete();
                $total = 0;
                foreach ($data['items'] as $item) {
                    $total += $item['quantity'] * $item['price'];
                    $order->items()->create($item);
                }
                $data['total_price'] = $total;
                unset($data['items']);
            }

            return $this->orderRepository->update($id, $data);
        });
    }

    /**
     * @throws LogicalException|NotFoundItemException
     */
    public function deleteOrder(int $id): bool
    {
        $order = $this->orderRepository->find($id)->load('payments');

        if ($order->payments->isNotEmpty()) {
            throw new LogicalException('Cannot delete order with associated payments.');
        }

        return $this->orderRepository->delete($id);
    }

    public function getOrders(FilterDto $dto): LengthAwarePaginator
    {
        $conditions = [];
        if (request()->has('status')) {
            $conditions['status'] = request('status');
        }

        return $this->orderRepository->paginateFiltered($dto, ['items'], $conditions);
    }

    public function getOrder(int $id): Order
    {
        return $this->orderRepository->find($id)->load(['items', 'payments']);
    }
}
