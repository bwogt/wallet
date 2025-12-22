<?php

namespace App\Enum\Transaction;

enum TransactionStatus: string
{
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case CONTESTED = 'contested';

    public function isCompleted(): bool
    {
        return $this === self::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    public function isContested(): bool
    {
        return $this === self::CONTESTED;
    }
}
