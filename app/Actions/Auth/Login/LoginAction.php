<?php

namespace App\Actions\Auth\Login;

use App\Actions\Validator\AuthValidator;
use App\Dto\Auth\Login\CredentialDTO;
use App\Dto\Auth\Login\LoginDTO;
use App\Models\User;

class LoginAction
{
    public function __invoke(CredentialDTO $credential): LoginDTO
    {
        $user = $this->searchUserByEmail($credential);
        $this->validateCredentials($user, $credential);
        $this->destroyAllTokens($user);

        return new LoginDTO($user, $this->createToken($user));
    }

    private function searchUserByEmail(CredentialDTO $credential): ?User
    {
        return User::where('email', $credential->email)->first();
    }

    private function validateCredentials(?User $user, CredentialDTO $credential): void
    {
        AuthValidator::for($user, $credential)
            ->credentialMustBeValid();
    }

    private function destroyAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    private function createToken(User $user): string
    {
        return $user->createToken('auth-token')->plainTextToken;
    }
}
