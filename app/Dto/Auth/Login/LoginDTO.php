<?php

namespace App\Dto\Auth\Login;

use App\Models\User;

class LoginDTO
{
    public function __construct(
        public readonly User $user,
        public readonly string $token,
    ) {}
}
