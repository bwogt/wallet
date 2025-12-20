<?php

namespace App\Actions\Auth\Register;

use App\Dto\Auth\Register\RegisterUserDTOInterface;
use App\Enum\User\UserType;
use App\Models\User;

class RegisterConsumerAction extends BaseRegisterUserAction
{
    protected function createUser(RegisterUserDTOInterface $data): User
    {
        return User::create([
            'type' => UserType::CONSUMER,
            'name' => $data->getName(),
            'email' => $data->getEmail(),
            'password' => $data->getPassword(),
            'cpf' => $data->getDocument(),
        ]);
    }
}
