<?php

namespace App\Dto\Auth\Login;

class CredentialDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}
