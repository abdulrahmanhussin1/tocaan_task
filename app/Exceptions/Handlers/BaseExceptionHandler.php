<?php

namespace App\Exceptions\Handlers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

abstract class BaseExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @inheritDoc
     */
    final public static function handle(Request $request, Throwable $exception): JsonResponse
    {
        if (static::shouldLog($request, $exception)) {
            report($exception);
        }
        return static::render($request, $exception);
    }

    /**
     * @inheritDoc
     */
    public static function shouldLog(Request $request, Throwable $exception): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public static function render(Request $request, Throwable $exception): JsonResponse
    {
        $payload = static::getPayload($request, $exception);
        return response()->json($payload, static::getResponseStatusCode($exception));
    }

    /**
     * @inheritDoc
     */
    public static function getPayload(Request $request, Throwable $exception): array
    {
        return [
            'errors' => [
                'message' => $exception->getMessage(),
                'actionCode' => static::getResponseStatusCode($exception)
            ]
        ];
    }
}
