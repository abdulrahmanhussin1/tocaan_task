<?php

namespace App\Services\V1;

use App\DTOs\V1\User\UserDto;
use App\Models\User;
use App\Repositories\V1\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function register(UserDto $dto): User
    {
        $data = $dto->toArray();
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->first(['email' => $email]);
    }
}
