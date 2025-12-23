<?php

namespace App\Exceptions\Deposit;

use DomainException;

class InvalidDepositAmountException extends DomainException
{
    public function __construct(
        ?string $message = 'Invalid deposit amount.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
