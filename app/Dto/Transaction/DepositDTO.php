<?php

namespace App\Dto\Transaction;

class DepositDTO
{
    public function __construct(
        public readonly string $user_id,
        public readonly float $value,
    ) {}
}
