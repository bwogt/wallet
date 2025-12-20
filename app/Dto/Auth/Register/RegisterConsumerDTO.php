<?php

namespace App\Dto\Auth\Register;

class RegisterConsumerDTO implements RegisterUserDTOInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $cpf,
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
        return $this->cpf;
    }
}
