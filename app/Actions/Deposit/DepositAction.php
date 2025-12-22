<?php

namespace App\Actions\Deposit;

use App\Actions\Validator\DepositLimitsValidator;
use App\Actions\Validator\UserValidator;
use App\Dto\Transaction\DepositDTO;
use App\Enum\Transaction\TransactionStatus;
use App\Enum\Transaction\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepositAction
{
    public function __invoke(DepositDTO $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $user = $this->searchUserById($data->user_id);
            $this->validateDepositRules($user, $data);

            $transaction = $this->createTransaction($data);
            $this->addBalanceToWallet($user, $data);
            $this->logSuccess($transaction);

            return $transaction;
        });
    }

    private function validateDepositRules(?User $user, DepositDTO $data): void
    {
        UserValidator::for($user)->userMustExist();

        DepositLimitsValidator::check($data->value)
            ->valueMustBeAboveMinimum()
            ->valueMustBeLessThanMaximum();
    }

    private function searchUserById(int $id): ?User
    {
        return User::where('id', $id)->lockForUpdate()->first();
    }

    private function createTransaction(DepositDTO $data): Transaction
    {
        return Transaction::create([
            'type' => TransactionType::DEPOSIT,
            'status' => TransactionStatus::COMPLETED,
            'payer_id' => $data->user_id,
            'payee_id' => $data->user_id,
            'value' => $data->value,
        ]);
    }

    private function addBalanceToWallet(User $user, DepositDTO $data): void
    {
        $user->wallet()->increment('balance', $data->value);
    }

    private function logSuccess(Transaction $transaction): void
    {
        Log::info('Deposit successful', [
            'transaction_id' => $transaction->id,
            'status' => $transaction->status,
            'payer_id' => $transaction->payer_id,
            'value' => $transaction->value,
        ]);
    }
}
