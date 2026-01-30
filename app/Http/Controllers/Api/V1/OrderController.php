<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\General\LogicalException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Order\CreateOrderRequest;
use App\Http\Requests\Api\V1\Order\UpdateOrderRequest;
use App\Services\V1\OrderService;
use App\DTOs\FilterDto;
use App\DTOs\V1\Order\OrderDto;
use App\Http\Resources\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->getOrders(FilterDto::fromRequest($request));

        return apiResponse(
            message: 'Orders retrieved successfully',
            data: OrderResource::collection($orders)->response()->getData(true)
        );
    }

    public function store(CreateOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder(OrderDto::fromRequest($request));

        return apiResponse(
            message: 'Order created successfully',
            data: new OrderResource($order)
        );
    }

    public function show(int $id): JsonResponse
    {
        $order = $this->orderService->getOrder($id);

        return apiResponse(
            message: 'Order details retrieved successfully',
            data: new OrderResource($order)
        );
    }

    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        $order = $this->orderService->updateOrder($id, $request->validated());

        return apiResponse(
            message: 'Order updated successfully',
            data: new OrderResource($order)
        );
    }

    /**
     * @throws LogicalException
     */
    public function destroy(int $id): JsonResponse
    {
        $this->orderService->deleteOrder($id);

        return apiResponse(
            message: 'Order deleted successfully'
        );
    }
}
