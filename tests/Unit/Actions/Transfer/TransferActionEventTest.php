<?php

namespace Tests\Unit\Actions\Transfer;

use App\Actions\Transfer\TransferAction;
use App\Dto\Transaction\Transfer\TransferDTO;
use App\Events\Transaction\Transfer\TransferCompleted;
use Illuminate\Support\Facades\Event;

class TransferActionEventTest extends TransferActionTestSetUp
{
    private TransferDTO $transferDto;
    protected function setUp(): void
    {
        parent::setUp();

        $this->transferDto = $this->createTransferDTO(
            payerId: $this->payer->id,
            payeeId: $this->payee->id,
            value: 100
        );
    }
    public function test_should_dispatch_transfer_completed_event_after_database_commit(): void
    {
        Event::fake();
        $this->authorizeTransfers();

        $action = app(TransferAction::class);
        $transfer = ($action)($this->transferDto);

        Event::assertDispatched(TransferCompleted::class, 1);

        Event::assertDispatched(TransferCompleted::class, function ($event) use ($transfer) {
            return $event->transaction->id === $transfer->id;
        });
    }

    public function test_should_not_dispatch_transfer_completed_event_if_transaction_fails(): void
    {
        Event::fake();
        $this->denyTransfers();

        $action = app(TransferAction::class);

        try {
            ($action)($this->transferDto);
        } catch (\Exception $e) {
        }

        Event::assertNotDispatched(TransferCompleted::class);
    }
}
