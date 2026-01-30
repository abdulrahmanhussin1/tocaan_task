<?php

namespace App\DTOs\V1\User;

use Illuminate\Http\Request;

readonly class UserDto
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]);
    }
}
