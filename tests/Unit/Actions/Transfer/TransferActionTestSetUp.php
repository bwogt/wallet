<?php

namespace Tests\Unit\Actions\Transfer;

use App\Actions\Transfer\TransferAction;
use App\Dto\Transaction\Transfer\TransferDTO;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransferActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $payer;
    protected User $payee;
    protected TransferAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userSetUp();
        $this->actionSetUp();
    }

    private function userSetUp(): void
    {
        $this->payer = UserFactory::new()
            ->consumer()
            ->withWalletBalance(250.25)
            ->create();

        $this->payee = UserFactory::new()
            ->consumer()
            ->withWalletBalance(100.10)
            ->create();
    }

    private function actionSetUp(): void
    {
        $this->action = new TransferAction;
    }

    protected function createTransferDTO(User $payer, User $payee, float $value): TransferDTO
    {
        return new TransferDTO(
            payer_id: $payer->id,
            payee_id: $payee->id,
            value: $value
        );
    }
}
