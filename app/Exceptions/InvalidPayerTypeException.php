<?php

namespace App\Exceptions;

use DomainException;

class InvalidPayerTypeException extends DomainException
{
    public function __construct(
        ?string $message = 'Invalid payer type.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
