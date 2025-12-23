<?php

namespace App\Actions\Validator;

use App\Dto\Auth\Login\CredentialDTO;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthValidator
{
    public function __construct(
        private readonly ?User $user,
        private readonly CredentialDTO $credential,
    ) {}

    public static function for(?User $user, CredentialDTO $credential): self
    {
        return new self($user, $credential);
    }

    public function credentialMustBeValid(): self
    {
        $emailMatch = $this->credential->email == $this->user?->email;
        $passwordMatch = Hash::check($this->credential->password, $this->user?->password);

        throw_unless($emailMatch && $passwordMatch,
            new InvalidCredentialsException(trans('exceptions.invalid_credentials')));

        return $this;
    }
}
