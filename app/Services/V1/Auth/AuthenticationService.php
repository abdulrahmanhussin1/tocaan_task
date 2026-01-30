<?php

namespace App\Services\V1\Auth;

use App\DTOs\V1\Auth\LoginDto;
use App\Exceptions\Auth\AuthenticationException;
use App\Helpers\JwtHelper;
use App\Http\Resources\AuthenticationResource;
use App\Repositories\V1\UserRepository;
use Illuminate\Support\Facades\Auth;

readonly class AuthenticationService
{
    /**
     * @throws AuthenticationException
     */
    public function login(LoginDto $dto): array
    {
        $credentials = [
            'email' => $dto->email,
            'password' => $dto->password
        ];

        if (!$token = Auth::attempt($credentials)) {
            throw new AuthenticationException();
        }

        return [
            'accessToken' => $token,
            'tokenType' => 'bearer',
            'expiresIn' => config('jwt.ttl'),
            'user' => new AuthenticationResource(auth()->user()),
        ];
    }

    public function logout(): bool
    {
        return (bool)auth()->logout();
    }

    public function refreshJwtToken(): array
    {
        return [
            'accessToken' => JwtHelper::refreshToken(auth()->getToken(), auth()->user()),
            'tokenType' => 'bearer',
            'expiresIn' => config('jwt.ttl'),
        ];
    }
}
