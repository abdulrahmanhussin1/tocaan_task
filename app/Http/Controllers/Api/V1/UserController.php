<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\V1\User\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * Handle user registration
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userService->register(UserDto::fromRequest($request));

        return apiResponse(
            message: 'User registered successfully',
            data: new UserResource($user),
        );
    }
}
