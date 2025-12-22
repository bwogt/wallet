<?php

namespace Tests\Feature\Transactions\Deposit;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepositTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $consumer;
    protected User $merchant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userSetUp();
    }

    private function userSetUp(): void
    {
        $this->consumer = UserFactory::new()->consumer()->create();
        $this->merchant = UserFactory::new()->merchant()->create();
    }

    protected function route(): string
    {
        return route('api.v1.transactions.deposit');
    }
}
