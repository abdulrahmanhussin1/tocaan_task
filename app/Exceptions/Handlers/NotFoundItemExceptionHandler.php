<?php

namespace App\Exceptions\Handlers;

use App\Enums\HTTPStatusCodeEnum;
use Illuminate\Http\Request;
use Throwable;

class NotFoundItemExceptionHandler extends BaseExceptionHandler
{
    /**
     * @inheritDoc
     */
    public static function getResponseStatusCode(Throwable $exception): int
    {
        return HTTPStatusCodeEnum::NOT_FOUND->value;
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
