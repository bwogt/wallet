<?php

namespace App\Actions\Transfer;

use App\Actions\Validator\TransferLimitsValidator;
use App\Actions\Validator\TransferPayerValidator;
use App\Actions\Validator\UserValidator;
use App\Dto\Transaction\Transfer\TransferDTO;
use App\Enum\Transaction\TransactionStatus;
use App\Enum\Transaction\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransferAction
{
    public function __invoke(TransferDTO $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $payer = $this->searchUserById($data->payer_id);
            $payee = $this->searchUserById($data->payee_id);

            $this->validationTransferRules($payer, $payee, $data);

            $transaction = $this->createTransaction($data);
            $this->decrementPayerBalance($transaction, $data);
            $this->incrementPayeeBalance($transaction, $data);

            $this->logSuccess($transaction);

            return $transaction;
        });
    }

    private function searchUserById(int $id): ?User
    {
        return User::where('id', $id)->lockForUpdate()->first();
    }

    private function validationTransferRules(?User $payer, ?User $payee, TransferDTO $data): void
    {
        UserValidator::for($payer)->userMustExist();
        UserValidator::for($payee)->userMustExist();

        TransferPayerValidator::for($payer)
            ->mustBeConsumer()
            ->mustNotTransferYourSelf($payee);

        TransferLimitsValidator::check($data->value)
            ->valueMustBeAboveMinimum()
            ->valueMustBeLessThanMaximum();
    }

    private function createTransaction(TransferDTO $data): Transaction
    {
        return Transaction::create([
            'type' => TransactionType::TRANSFER,
            'status' => TransactionStatus::COMPLETED,
            'payer_id' => $data->payer_id,
            'payee_id' => $data->payee_id,
            'value' => $data->value,
        ]);
    }

    private function decrementPayerBalance(Transaction $transaction): void
    {
        $transaction->payer->wallet()->decrement('balance', $transaction->value);
    }

    private function incrementPayeeBalance(Transaction $transaction): void
    {
        $transaction->payee->wallet()->increment('balance', $transaction->value);
    }

    private function logSuccess(Transaction $transaction): void
    {
        Log::info('Transfer successful', [
            'transaction_id' => $transaction->id,
            'type' => $transaction->type,
            'status' => $transaction->status,
            'payer_id' => $transaction->payer_id,
            'payee' => $transaction->payee_id,
            'value' => $transaction->value,
        ]);
    }

}
