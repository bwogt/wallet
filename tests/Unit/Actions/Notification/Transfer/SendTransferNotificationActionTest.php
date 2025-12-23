<?php

namespace Tests\Unit\Actions\Notification\Transfer;

use App\Actions\Transfer\SendTransferNotificationAction;
use App\Exceptions\Notification\NotificationServiceUnavailableException;
use App\Models\Transaction;
use Database\Factories\TransactionFactory;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SendTransferNotificationActionTest extends TestCase
{
    private Transaction $transaction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionSetUp();
    }

    private function transactionSetUp(): void
    {
        $this->transaction = TransactionFactory::new()->transfer()->create();
    }

    private function notificationServiceAvailable(): void
    {
        Http::fake([
            config('services.notification.url') => Http::response([], 200),
        ]);
    }

    private function notificationServiceUnavailable(): void
    {
        Http::fake([
            config('services.notification.url') => Http::response([], 500),
        ]);
    }

    public function test_should_send_notification_successfully(): void
    {
        $this->notificationServiceAvailable();

        $action = app(SendTransferNotificationAction::class);
        ($action)($this->transaction);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST'
                && $request->url() === config('services.notification.url')
                && isset($request['title'])
                && $request['title'] === trans('notification.transfer.received.title')
                && isset($request['message'])
                && $request['message'] === trans('notification.transfer.received.message', [
                    'payer_name' => $this->transaction->payer->name,
                    'value' => $this->transaction->value,
                ]);
        });
    }

    public function test_should_throw_exception_when_notification_service_fails(): void
    {
        $this->notificationServiceUnavailable();
        $this->expectException(NotificationServiceUnavailableException::class);

        $action = app(SendTransferNotificationAction::class);
        ($action)($this->transaction);
    }
}
