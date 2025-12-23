<?php

namespace App\Listeners\Transaction\Transfer;

use App\Actions\Transfer\SendTransferNotificationAction;
use App\Events\Transaction\Transfer\TransferCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTransferNotification implements ShouldQueue
{
    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the queued listener.
     *
     * @var int
     */
    public $backoff = [3, 9, 27];

    /**
     * Create the event listener.
     */
    public function __construct(private SendTransferNotificationAction $action) {}

    /**
     * Handle the event.
     */
    public function handle(TransferCompleted $event): void
    {
        ($this->action)($event->transaction);
    }
}
