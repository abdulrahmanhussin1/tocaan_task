<?php

namespace App\Exceptions\Factories;

use App\Exceptions\Auth\AuthenticationException;
use App\Exceptions\General\LogicalException;
use App\Exceptions\General\NotFoundItemException;
use App\Exceptions\General\UnAuthorizedException;
use App\Exceptions\Handlers\AuthenticationExceptionHandler;
use App\Exceptions\Handlers\BadRequestExceptionHandler;
use App\Exceptions\Handlers\NotFoundItemExceptionHandler;
use App\Exceptions\Handlers\UnAuthorizedExceptionHandler;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ExceptionHandlerFactory
{
    private const EXCEPTION_HANDLERS = [
        AuthenticationException::class => AuthenticationExceptionHandler::class,
        TokenInvalidException::class => AuthenticationExceptionHandler::class,
        TokenExpiredException::class => AuthenticationExceptionHandler::class,
        LogicalException::class => BadRequestExceptionHandler::class,
        NotFoundItemException::class => NotFoundItemExceptionHandler::class,
        UnAuthorizedException::class => UnAuthorizedExceptionHandler::class,
    ];

    public static function getExceptionHandler($exception): ?string
    {
        foreach (self::EXCEPTION_HANDLERS as $exceptionClass => $handler) {
            if ($exception instanceof $exceptionClass) {
                return $handler;
            }
        }
        return null;
    }
}
