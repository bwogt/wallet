<?php

namespace Tests\Unit\Actions\Transfer;

use App\Actions\Transfer\TransferAction;
use App\Constants\Transfer\TransferConstants;
use App\Enum\Transaction\TransactionStatus;
use App\Enum\Transaction\TransactionType;
use App\Exceptions\Transfer\InsufficientFundsException;
use App\Exceptions\Transfer\InvalidPayerTypeException;
use App\Exceptions\Transfer\InvalidTransferValueException;
use App\Exceptions\Transfer\SelfTransferException;
use App\Exceptions\Transfer\TransferUnauthorizedException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\Transaction;
use Database\Factories\UserFactory;

class TransferActionTest extends TransferActionTestSetUp
{
    public function test_should_return_an_instance_of_transaction(): void
    {
        $this->authorizeTransfers();
        $action = app(TransferAction::class);

        $transferDto = $this->createTransferDTO(
            payerId: $this->payer->id,
            payeeId: $this->payee->id,
            value: 12.75
        );

        $transfer = ($action)($transferDto);
        $this->assertInstanceOf(Transaction::class, $transfer);
    }

    public function test_should_persist_the_transaction_transfer(): void
    {
        $this->authorizeTransfers();
        $action = app(TransferAction::class);

        $transferDto = $this->createTransferDTO(
            payerId: $this->payer->id,
            payeeId: $this->payee->id,
            value: 100
        );

        $transfer = ($action)($transferDto);

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
        $this->authorizeTransfers();
        $action = app(TransferAction::class);

        $this->assertEquals(100.10, $this->payee->wallet->balance);

        $transferDto = $this->createTransferDTO(
            payerId: $this->payer->id,
            payeeId: $this->payee->id,
            value: 1.50
        );

        ($action)($transferDto);
        $this->assertEquals(101.60, $this->payee->fresh()->wallet->balance);
    }

    public function test_should_decrement_the_payer_wallet_balance(): void
    {
        $this->authorizeTransfers();
        $action = app(TransferAction::class);

        $this->assertEquals(250.25, $this->payer->wallet->balance);

        $transferDto = $this->createTransferDTO(
            payerId: $this->payer->id,
            payeeId: $this->payee->id,
            value: 50.25
        );

        ($action)($transferDto);
        $this->assertEquals(200, $this->payer->fresh()->wallet->balance);
    }

    public function test_should_throw_an_exception_when_the_payer_user_does_not_exists(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(trans('exceptions.user_not_found'));

        $this->authorizeTransfers();
        $action = app(TransferAction::class);

        $transferDto = $this->createTransferDTO(
            payerId: 0,
            payeeId: $this->payee->id,
            value: 50.25
        );

        ($action)($transferDto);
    }

    public function test_should_throw_an_exception_when_the_payee_user_does_not_exists(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(trans('exceptions.user_not_found'));

        $this->authorizeTransfers();
        $action = app(TransferAction::class);

        $transferDto = $this->createTransferDTO(
            payerId: $this->payer->id,
            payeeId: 0,
            value: 50.25
        );

        ($action)($transferDto);
    }

    public function test_should_throw_an_exception_when_the_user_payer_is_merchant(): void
    {
        $this->expectException(InvalidPayerTypeException::class);
        $this->expectExceptionMessage(trans('exceptions.transfer_payer_must_be_consumer'));

        $this->authorizeTransfers();
        $action = app(TransferAction::class);

        $payer = UserFactory::new()->merchant()->create();

        $transferDto = $this->createTransferDTO(
            payerId: $payer->id,
            payeeId: $this->payee->id,
            value: 50.25
        );

        ($action)($transferDto);
    }

    public function test_should_throw_an_exception_when_value_is_less_than_the_minimum(): void
    {
        $this->expectException(InvalidTransferValueException::class);
        $this->expectExceptionMessage(trans('exceptions.transfer_value_below_minimum', [
            'minimum' => TransferConstants::MIN_VALUE,
        ]));

        $this->authorizeTransfers();
        $action = app(TransferAction::class);

        $transferDto = $this->createTransferDTO(
            payerId: $this->payer->id,
            payeeId: $this->payee->id,
            value: bcsub(TransferConstants::MIN_VALUE, '0.1', 2)
        );

        ($action)($transferDto);
    }

    public function test_should_throw_an_exception_when_value_is_greater_than_the_maximum(): void
    {
        $this->expectException(InvalidTransferValueException::class);
        $this->expectExceptionMessage(trans('exceptions.transfer_value_above_maximum', [
            'maximum' => TransferConstants::MAX_VALUE,
        ]));

        $this->authorizeTransfers();
        $action = app(TransferAction::class);

        $payer = UserFactory::new()
            ->consumer()
            ->withWalletBalance(TransferConstants::MAX_VALUE + 1)
            ->create();

        $transferDto = $this->createTransferDTO(
            payerId: $payer->id,
            payeeId: $this->payee->id,
            value: bcadd(TransferConstants::MAX_VALUE, '0.1', 2),
        );

        ($action)($transferDto);
    }

    public function test_should_throw_an_exception_when_the_payer_attempts_transfer_to_yourself(): void
    {
        $this->expectException(SelfTransferException::class);
        $this->expectExceptionMessage(trans('exceptions.transfer_cannot_send_to_self'));

        $this->authorizeTransfers();
        $action = app(TransferAction::class);

        $transferDto = $this->createTransferDTO(
            payerId: $this->payer->id,
            payeeId: $this->payer->id,
            value: 120,
        );

        ($action)($transferDto);
    }

    public function test_should_throw_an_exception_when_the_payer_does_not_have_sufficient_funds(): void
    {
        $this->expectException(InsufficientFundsException::class);
        $this->expectExceptionMessage(trans('exceptions.transfer_insufficient_funds'));

        $this->authorizeTransfers();
        $action = app(TransferAction::class);

        $transferDto = $this->createTransferDTO(
            payerId: $this->payer->id,
            payeeId: $this->payee->id,
            value: $this->payer->wallet->balance + 0.01,
        );

        ($action)($transferDto);
    }

    public function test_should_throw_an_exception_when_the_transfer_is_not_authorized(): void
    {
        $this->expectException(TransferUnauthorizedException::class);
        $this->expectExceptionMessage(trans('exceptions.transfer_unauthorized'));

        $this->denyTransfers();
        $action = app(TransferAction::class);

        $transferDto = $this->createTransferDTO(
            payerId: $this->payer->id,
            payeeId: $this->payee->id,
            value: 50.25
        );

        ($action)($transferDto);
    }
}
