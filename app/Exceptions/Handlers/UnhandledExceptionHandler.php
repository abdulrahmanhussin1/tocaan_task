<?php

namespace App\Exceptions\Handlers;

use App\Enums\AppEnvironmentEnum;
use App\Enums\HTTPStatusCodeEnum;
use Illuminate\Http\Request;
use PDOException;
use Throwable;

class UnhandledExceptionHandler extends BaseExceptionHandler
{
    /**
     * @inheritDoc
     */
    public static function shouldLog(Request $request, Throwable $exception): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function getResponseStatusCode(Throwable $exception): int
    {
        return HTTPStatusCodeEnum::INTERNAL_SERVER_ERROR->value;
    }

    public static function getPayload(Request $request, Throwable $exception): array
    {
        $payload = parent::getPayload($request, $exception);
        if ($exception instanceof PDOException && config('app.env') === AppEnvironmentEnum::PRODUCTION->value) {
            $payload['errors']['message'] = "Internal server error.";
        }
        return $payload;
    }
}
