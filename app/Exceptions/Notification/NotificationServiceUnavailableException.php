<?php

namespace App\Exceptions\Notification;

use RuntimeException;

class NotificationServiceUnavailableException extends RuntimeException
{
    public function __construct(
        ?string $message = 'Notification service unavailable.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
