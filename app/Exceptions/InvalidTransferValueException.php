<?php

namespace App\Exceptions;

use Exception;

class InvalidTransferValueException extends Exception
{
    public function __construct(
        ?string $message = 'Invalid transfer value.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
