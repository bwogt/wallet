<?php

namespace App\Actions\Auth\Register;

use App\Dto\Auth\Login\LoginDTO;
use App\Dto\Auth\Register\RegisterUserDTOInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

abstract class BaseRegisterUserAction
{
    public function __invoke(RegisterUserDTOInterface $data): LoginDTO
    {
        return DB::transaction(function () use ($data) {
            $user = $this->createUser($data);
            $this->createWallet($user);

            return new LoginDTO(
                user: $user,
                token: $this->createPersonalAccessToken($user)
            );
        });
    }

    abstract protected function createUser(RegisterUserDTOInterface $data): User;

    protected function createWallet(User $user): void
    {
        $user->wallet()->create();
    }

    protected function createPersonalAccessToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
