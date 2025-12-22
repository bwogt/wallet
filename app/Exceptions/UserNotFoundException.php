<?php

namespace App\Exceptions;

use DomainException;

class UserNotFoundException extends DomainException
{
    public function __construct(
        ?string $message = 'Invalid deposit amount.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
