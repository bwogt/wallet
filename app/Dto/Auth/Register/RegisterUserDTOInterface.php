<?php

namespace App\Dto\Auth\Register;

interface RegisterUserDTOInterface
{
    public function getName(): string;
    public function getEmail(): string;
    public function getPassword(): string;
    public function getDocument(): string;
}
