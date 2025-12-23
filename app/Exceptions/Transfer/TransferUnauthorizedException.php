<?php

namespace App\Exceptions\Transfer;

use DomainException;

class TransferUnauthorizedException extends DomainException
{
    public function __construct(
        ?string $message = 'Transfer unauthorized.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
