<?php

namespace Tests\Unit\Actions\Deposit;

use App\Actions\Deposit\DepositAction;
use App\Constants\Deposit\DepositConstants;
use App\Dto\Transaction\Deposit\DepositDTO;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepositActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected DepositAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::new()->consumer()->create();
        $this->action = new DepositAction;
    }

    protected function depositData(?int $userId = null, ?float $value = null): DepositDTO
    {
        return new DepositDTO(
            user_id: $userId ?? $this->user->id,
            value: $value ?? DepositConstants::MIN_VALUE
        );
    }
}
