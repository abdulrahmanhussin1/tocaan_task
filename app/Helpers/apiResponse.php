<?php

use App\Enums\HTTPStatusCodeEnum;
use Illuminate\Http\JsonResponse;


function apiResponse(bool $success = true, int $statusCode = HTTPStatusCodeEnum::OK->value ,string $message ='',mixed $data = []): JsonResponse
{
    $response = [
        'success' => $success,
        'message' => __($message),
    ];

    if (!empty($data)) {
        $response['data'] = $data;
    }

    return response()->json($response, $statusCode);
}


