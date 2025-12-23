<?php

namespace App\Enum\Transaction;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case TRANSFER = 'transfer';

    public function isDeposit(): bool
    {
        return $this === self::DEPOSIT;
    }

    public function isTransfer(): bool
    {
        return $this === self::TRANSFER;
    }
}
