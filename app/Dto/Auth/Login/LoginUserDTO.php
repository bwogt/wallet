<?php

namespace App\Dto\Auth\Login;

use App\Models\User;

class LoginUserDTO
{
    public function __construct(
        public readonly User $user,
        public readonly string $token,
    ){}
}