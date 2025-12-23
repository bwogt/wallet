<?php

namespace Tests\Feature\Transactions\Transfer;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TransferTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $payer;
    protected User $payee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userSetUp();
        $this->authorizeTransfers();
    }

    private function userSetUp(): void
    {
        $this->payer = UserFactory::new()
            ->consumer()
            ->withWalletBalance(100)
            ->create();

        $this->payee = UserFactory::new()
            ->consumer()
            ->create();
    }

    private function authorizeTransfers(): void
    {
        Http::fake(fn () => Http::response([
            'data' => ['authorization' => true],
        ], Response::HTTP_OK));
    }

    protected function route(): string
    {
        return route('api.v1.transactions.transfer');
    }
}
