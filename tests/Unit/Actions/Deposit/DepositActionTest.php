<?php

namespace Tests\Unit\Actions\Deposit;

use App\Constants\Deposit\DepositConstants;
use App\Enum\Transaction\TransactionStatus;
use App\Enum\Transaction\TransactionType;
use App\Exceptions\InvalidDepositAmountException;
use App\Exceptions\UserNotFoundException;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DepositActionTest extends DepositActionTestSetUp
{
    public function test_should_return_an_instance_of_transaction(): void
    {
        $deposit = ($this->action)($this->depositData());
        $this->assertInstanceOf(Transaction::class, $deposit);
    }

    public function test_should_persist_the_deposit(): void
    {
        $deposit = ($this->action)($this->depositData());

        $this->assertDatabaseHas('transactions', [
            'id' => $deposit->id,
            'type' => TransactionType::DEPOSIT,
            'status' => TransactionStatus::COMPLETED,
            'payer_id' => $this->depositData()->user_id,
            'payee_id' => $this->depositData()->user_id,
            'amount' => $this->depositData()->amount,
        ]);
    }

    public function test_should_increment_the_wallet_balance(): void
    {
        $this->assertEquals('0.00', $this->user->wallet->balance);

        ($this->action)($this->depositData(amount: 12.75));
        $this->assertEquals(12.75, $this->user->fresh()->wallet->balance);

        ($this->action)($this->depositData(amount: 250.93));
        $this->assertEquals(263.68, $this->user->fresh()->wallet->balance);
    }

    public function test_should_possible_to_deposit_the_minimum_amount(): void
    {
        $deposit = ($this->action)($this->depositData(amount: DepositConstants::MIN_AMOUNT));

        $this->assertDatabaseHas('transactions', [
            'id' => $deposit->id,
            'amount' => DepositConstants::MIN_AMOUNT,
            'status' => TransactionStatus::COMPLETED,
        ]);
    }

    public function test_should_possible_to_deposit_the_max_amount(): void
    {
        $deposit = ($this->action)($this->depositData(amount: DepositConstants::MAX_AMOUNT));

        $this->assertDatabaseHas('transactions', [
            'id' => $deposit->id,
            'amount' => DepositConstants::MAX_AMOUNT,
            'status' => TransactionStatus::COMPLETED,
        ]);
    }

    public function test_should_throw_an_exception_when_user_does_not_exists(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(trans('exceptions.user_not_found'));

        ($this->action)($this->depositData(userId: '0'));
    }

    public function test_should_throw_an_exception_when_amount_is_less_than_the_minimum(): void
    {
        $this->expectException(InvalidDepositAmountException::class);
        $this->expectExceptionMessage(trans('exceptions.deposit_amount_below_minimum', [
            'minimum' => DepositConstants::MIN_AMOUNT,
        ]));

        $amount = bcsub(DepositConstants::MIN_AMOUNT, '0.1', 2);
        ($this->action)($this->depositData(amount: $amount));
    }

    public function test_should_throw_an_exception_when_amount_is_greater_than_the_maximum(): void
    {
        $this->expectException(InvalidDepositAmountException::class);
        $this->expectExceptionMessage(trans('exceptions.deposit_amount_above_maximum', [
            'maximum' => DepositConstants::MAX_AMOUNT,
        ]));

        $amount = bcadd(DepositConstants::MAX_AMOUNT, '0.1', 2);
        ($this->action)($this->depositData(amount: $amount));
    }

    public function test_should_propagate_exception_when_database_transaction_fails(): void
    {
        $message = 'Simulates a DB error';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($message);

        DB::shouldReceive('transaction')->once()
            ->andThrow(new Exception($message,
                Response::HTTP_INTERNAL_SERVER_ERROR
            ));

        ($this->action)($this->depositData());
    }
}
