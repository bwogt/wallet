<?php

namespace App\Enum\Deposit;

enum FlashMessageType: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case WARNING = 'warning';
    case INFO = 'info';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isSuccess(): bool
    {
        return $this === self::SUCCESS;
    }

    public function isError(): bool
    {
        return $this === self::ERROR;
    }

    public function isWarning(): bool
    {
        return $this === self::WARNING;
    }

    public function isInfo(): bool
    {
        return $this === self::INFO;
    }
}