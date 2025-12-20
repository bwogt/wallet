<?php

namespace App\Actions\Auth\Register;

use App\Dto\Auth\Register\RegisterUserDTOInterface;
use App\Enum\User\UserType;
use App\Models\User;

class RegisterMerchantAction extends BaseRegisterUserAction
{
    protected function createUser(RegisterUserDTOInterface $data): User
    {
        return User::create([
            'type' => UserType::MERCHANT,
            'name' => $data->getName(),
            'email' => $data->getEmail(),
            'password' => $data->getPassword(),
            'cnpj' => $data->getDocument(),
        ]);
    }
}
