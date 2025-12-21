<?php

namespace App\Exceptions;

use DomainException;

class InvalidCredentialsException extends DomainException
{
    public function __construct(
        ?string $message = 'Invalid Credentials',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
