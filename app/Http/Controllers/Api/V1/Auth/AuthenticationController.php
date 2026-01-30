<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTOs\V1\Auth\LoginDto;
use App\Exceptions\Auth\AuthenticationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Services\V1\Auth\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $authService
    ) {
    }

    /**
     * Handle user login
     * @throws AuthenticationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return apiResponse(
            message: 'Login successful',
            data: $this->authService->login(LoginDto::fromRequest($request)),
        );
    }

    /**
     * Handle user logout
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return apiResponse(
            message: 'Logout Successful'
        );
    }

    public function refreshToken(): JsonResponse
    {
        return apiResponse(
            message: 'Refresh token successful',
            data: $this->authService->refreshJwtToken()
        );
    }
}
