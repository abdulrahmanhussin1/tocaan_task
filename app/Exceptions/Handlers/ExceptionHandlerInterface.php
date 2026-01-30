<?php

namespace App\Exceptions\Handlers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

interface ExceptionHandlerInterface
{
    /**
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse
     */
    public static function handle(Request $request, Throwable $exception): JsonResponse;

    /**
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse
     */
    public static function render(Request $request, Throwable $exception): JsonResponse;

    /**
     * @param Request $request
     * @param Throwable $exception
     * @return bool
     */
    public static function shouldLog(Request $request, Throwable $exception): bool;

    /**
     * @param Request $request
     * @param Throwable $exception
     * @return array
     */
    public static function getPayload(Request $request, Throwable $exception): array;

    /**
     * @param Throwable $exception
     * @return int
     */
    public static function getResponseStatusCode(Throwable $exception): int;
}
