<?php

namespace App\Enum\Transaction;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';

    public function isDeposit(): bool
    {
        return $this === self::DEPOSIT;
    }
}
