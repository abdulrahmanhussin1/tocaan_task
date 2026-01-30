<?php

namespace App\Helpers;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtHelper
{
    public static function refreshToken(string $oldToken, User $user): string
    {
        return JWTAuth::setToken($oldToken)
            ->invalidate()
            ->fromUser($user);
    }
}
