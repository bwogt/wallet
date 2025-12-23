<?php

namespace App\Exceptions\Transfer;

use DomainException;

class InsufficientFundsException extends DomainException
{
    public function __construct(
        ?string $message = 'User does not have the necessary funds',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
