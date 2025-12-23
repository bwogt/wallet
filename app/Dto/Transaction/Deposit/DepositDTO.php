<?php

namespace App\Dto\Transaction\Deposit;

class DepositDTO
{
    public function __construct(
        public readonly string $user_id,
        public readonly float $value,
    ) {}
}
