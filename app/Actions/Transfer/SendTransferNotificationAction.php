<?php

namespace App\Actions\Transfer;

use App\Exceptions\Notification\NotificationServiceUnavailableException;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;

class SendTransferNotificationAction
{
    public function __invoke(Transaction $transaction): void
    {
        $response = Http::timeout(5)->post(
            config('services.notification.url'),
            [
                'title' => trans('notification.transfer.received.title'),
                'message' => trans('notification.transfer.received.message', [
                    'payer_name' => $transaction->payer->name,
                    'value' => $transaction->value,
                ]),
            ]
        );

        if (! $response->successful()) {
            throw new NotificationServiceUnavailableException;
        }
    }
}
