<?php

namespace Tests\Unit\Listeners\Transaction\Transfer;

use App\Actions\Transfer\SendTransferNotificationAction;
use App\Events\Transaction\Transfer\TransferCompleted;
use App\Listeners\Transaction\Transfer\SendTransferNotification;
use Database\Factories\TransactionFactory;
use Mockery;
use Tests\TestCase;

class SendTransferNotificationListenerTest extends TestCase
{
    public function test_should_call_notification_action_when_event_is_called(): void
    {
        $transaction = TransactionFactory::new()->transfer()->create();
        $event = new TransferCompleted($transaction);

        $actionMock = Mockery::mock(SendTransferNotificationAction::class);

        $actionMock->shouldReceive('__invoke')
            ->once()
            ->with($transaction);

        $listener = new SendTransferNotification($actionMock);
        $listener->handle($event);
    }
}
