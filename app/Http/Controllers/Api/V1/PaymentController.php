<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\General\LogicalException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Payment\PaymentRequest;
use App\Services\V1\Payment\PaymentService;
use App\DTOs\V1\Payment\PaymentDto;
use App\Http\Resources\PaymentResource;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {
    }

    public function index(): JsonResponse
    {
        return apiResponse(
            message: 'Payments retrieved successfully',
            data: PaymentResource::collection($this->paymentService->getAllPayments())
        );
    }

    /**
     * @throws LogicalException
     */
    public function process(PaymentRequest $request): JsonResponse
    {
        $payment = $this->paymentService->processPayment(
            PaymentDto::fromRequest($request)
        );

        return apiResponse(
            message: 'Payment processed successfully',
            data: new PaymentResource($payment)
        );
    }

    public function showByOrder(int $orderId): JsonResponse
    {
        return apiResponse(
            message: 'Order payments retrieved successfully',
            data: PaymentResource::collection($this->paymentService->getOrderPayments($orderId))
        );
    }
}
