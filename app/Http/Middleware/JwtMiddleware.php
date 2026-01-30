<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return $this->unauthorized('Invalid or expired token');
            }

            return $next($request);

        } catch (TokenExpiredException $e) {
            return $this->unauthorized('Token has expired');
        } catch (JWTException $e) {
            return $this->unauthorized('Token is invalid');
        } catch (\Exception $e) {
            return $this->error('An unexpected error occurred', 500);
        }
    }

    /**
     * Return unauthorized response
     */
    protected function unauthorized(string $message): JsonResponse
    {
        return apiResponse(false, 401, $message);
    }

    /**
     * Return error response
     */
    protected function error(string $message, int $status): JsonResponse
    {
        return apiResponse(false, $status, $message);
    }
}
