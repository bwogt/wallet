<?php

namespace App\Exceptions;

use Exception;

class SelfTransferException extends Exception
{
    public function __construct(
        ?string $message = 'users cannot transfer money to themselves',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
