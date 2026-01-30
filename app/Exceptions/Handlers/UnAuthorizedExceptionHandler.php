<?php

namespace App\Exceptions\Handlers;

use App\Enums\HTTPStatusCodeEnum;
use Illuminate\Http\Request;
use Throwable;

class UnAuthorizedExceptionHandler extends BaseExceptionHandler
{
    /**
     * @inheritDoc
     */
    public static function getResponseStatusCode(Throwable $exception): int
    {
        return HTTPStatusCodeEnum::UNAUTHORIZED->value;
    }

    /**
     * @inheritDoc
     */
    public static function getPayload(Request $request, Throwable $exception): array
    {
        return [
            'success' => false,
            'message' => $exception->getMessage() ,
        ];
    }
}
