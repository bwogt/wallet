<?php

namespace App\Exceptions\Transfer;

use DomainException;

class InvalidTransferValueException extends DomainException
{
    public function __construct(
        ?string $message = 'Invalid transfer value.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
