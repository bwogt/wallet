<?php

namespace App\Actions\Validator;

use App\Exceptions\UserNotFoundException;
use App\Models\User;

class UserValidator
{
    public function __construct(
        public readonly ?User $user
    ) {}

    public static function for(?User $user): self
    {
        return new self($user);
    }

    public function userMustExist(): self
    {
        throw_unless($this->user, new UserNotFoundException(
            trans('exceptions.user_not_found')
        ));

        return $this;
    }
}
