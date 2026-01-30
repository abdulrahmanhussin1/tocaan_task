<?php

namespace App\Enums;


enum HTTPStatusCodeEnum: int
{
    case OK = 200;
    case CREATED = 201;
    case BAD_REQUEST = 400;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case UNPROCESSABLE_ENTITY = 422;
    case UNAUTHORIZED = 401;
    case REQUEST_TIMEOUT = 408;
    case MANY_REQUESTS = 429;
    case PAYLOAD_LARGE = 413;
    case NOT_MODIFIED = 304;
    case INTERNAL_SERVER_ERROR = 500;
    case BAD_GATEWAY = 502;
    case SERVICE_UNAVAILABLE = 503;
    case GATEWAY_TIMEOUT = 504;
    case GONE = 410;
}
