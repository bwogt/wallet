<?php

namespace App\Actions\Auth\Register;

use App\Dto\Auth\Register\RegisterUserDTOInterface;
use App\Models\User;

class RegisterMerchantAction extends BaseRegisterUserAction
{
    protected function createUser(RegisterUserDTOInterface $data): User
    {
        return User::create([
            'type' => $data->getType(),
            'name' => $data->getName(),
            'email' => $data->getEmail(),
            'password' => bcrypt($data->getPassword()),
            'cnpj' => $data->getDocument(),
        ]);
    }
}
