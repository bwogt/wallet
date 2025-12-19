<?php

namespace App\Dto\Auth\Register;

use App\Enum\User\UserType;

class RegisterMerchantDTO implements RegisterUserDTOInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $cnpj,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDocument(): string
    {
        return $this->cnpj;
    }

    public function getType(): string
    {
        return UserType::MERCHANT->value;
    }
}
