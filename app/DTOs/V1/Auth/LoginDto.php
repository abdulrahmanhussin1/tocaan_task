<?php

namespace App\DTOs\V1\Auth;

use Illuminate\Http\Request;

readonly class LoginDto
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->input('email'),
            password: $request->input('password'),
        );
    }
}
