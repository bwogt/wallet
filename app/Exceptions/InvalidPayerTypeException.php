<?php

namespace App\Exceptions;

use Exception;

class InvalidPayerTypeException extends Exception
{
    public function __construct(
        ?string $message = 'Invalid payer type.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
