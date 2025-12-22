<?php

namespace Tests\Unit\Actions\Transfer;

use App\Enum\Transaction\TransactionStatus;
use App\Enum\Transaction\TransactionType;
use App\Models\Transaction;

class TransferActionTest extends TransferActionTestSetUp
{
    public function test_should_return_an_instance_of_transaction(): void
    {
        $transferDto = $this->createTransferDTO($this->payer, $this->payee, 12.75);
        $transfer = ($this->action)($transferDto);

        $this->assertInstanceOf(Transaction::class, $transfer);
    }

    public function test_should_persist_the_transaction_transfer(): void
    {
        $transferDto = $this->createTransferDTO($this->payer, $this->payee, 100);
        $transfer = ($this->action)($transferDto);

        $this->assertDatabaseHas('transactions', [
            'id' => $transfer->id,
            'type' => TransactionType::TRANSFER,
            'status' => TransactionStatus::COMPLETED,
            'payer_id' => $this->payer->id,
            'payee_id' => $this->payee->id,
            'value' => 100,
        ]);
    }

    public function test_should_increment_the_payee_wallet_balance(): void
    {
        $this->assertEquals(100.10, $this->payee->wallet->balance);

        $transferDto = $this->createTransferDTO($this->payer, $this->payee, 1.50);

        ($this->action)($transferDto);
        $this->assertEquals(101.60, $this->payee->fresh()->wallet->balance);
    }

    public function test_should_decrement_the_payer_wallet_balance(): void
    {
        $this->assertEquals(250.25, $this->payer->wallet->balance);

        $transferDto = $this->createTransferDTO($this->payer, $this->payee, 50.25);

        ($this->action)($transferDto);
        $this->assertEquals(200, $this->payer->fresh()->wallet->balance);
    }
}
