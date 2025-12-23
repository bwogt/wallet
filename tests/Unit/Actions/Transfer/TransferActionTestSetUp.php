<?php

namespace Tests\Unit\Actions\Transfer;

use App\Dto\Transaction\Transfer\TransferDTO;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TransferActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $payer;
    protected User $payee;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userSetUp();
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

    protected function createTransferDTO(int $payerId, int $payeeId, float $value): TransferDTO
    {
        return new TransferDTO(
            payer_id: $payerId,
            payee_id: $payeeId,
            value: $value
        );
    }

    protected function authorizeTransfers(): void
    {
        Http::fake(fn () => Http::response([
            'data' => ['authorization' => true],
        ], Response::HTTP_OK));
    }

    protected function denyTransfers(): void
    {
        Http::fake(fn () => Http::response([
            'data' => ['authorization' => false],
        ], Response::HTTP_OK));
    }
}
