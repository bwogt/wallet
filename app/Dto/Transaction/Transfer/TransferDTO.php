<?php

namespace App\Dto\Transaction\Transfer;

class TransferDTO
{
    public function __construct(
        public readonly string $payer_id,
        public readonly string $payee_id,
        public readonly float $value,
    ) {}
}
