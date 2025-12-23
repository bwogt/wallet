<?php

namespace Tests\Feature\Transactions\Transfer;

use App\Constants\Transfer\TransferConstants;
use App\Enum\FlashMessage\FlashMessageType;
use App\Enum\Transaction\TransactionStatus;
use App\Enum\Transaction\TransactionType;
use Database\Factories\UserFactory;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class TransferAccessTest extends TransferTestSetUp
{
    public function test_an_unauthenticated_user_should_not_be_able_to_transfer_funds(): void
    {
        $this->postJson($this->route(), [
            'payee_id' => $this->payee->id,
            'value' => TransferConstants::MIN_VALUE,
        ])
            ->assertUnauthorized()
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                ->where('message.text', trans('http_exceptions.unauthenticated'))
            );
    }

    public function test_a_consumer_user_should_be_able_to_transfer_funds(): void
    {
        Sanctum::actingAs($this->payer);

        $this->postJson($this->route(), [
            'payee_id' => $this->payee->id,
            'value' => TransferConstants::MIN_VALUE,
        ])
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::SUCCESS)
                ->where('message.text', trans('flash_messages.success.transfer'))
                ->where('data.type', TransactionType::TRANSFER->value)
                ->where('data.status', TransactionStatus::COMPLETED->value)
                ->where('data.value', fn ($value) => (float) $value === (float) TransferConstants::MIN_VALUE)
                ->has('data.created_at')
                ->has('data.updated_at')

                ->has('data.payer')
                ->where('data.payer.id', $this->payer->id)
                ->where('data.payer.name', $this->payer->name)
                ->where('data.payer.email', $this->payer->email)
                ->where('data.payer.cpf', $this->payer->cpf)

                ->has('data.payee')
                ->where('data.payee.id', $this->payee->id)
                ->where('data.payee.name', $this->payee->name)
                ->where('data.payee.email', $this->payee->email)
                ->where('data.payee.cpf', $this->payee->cpf)
            );
    }

    public function test_a_merchant_user_should_not_be_able_to_transfer_funds(): void
    {
        $merchant = UserFactory::new()->merchant()->create();
        Sanctum::actingAs($merchant);

        $this->postJson($this->route(), [
            'payee_id' => $this->payee->id,
            'value' => TransferConstants::MIN_VALUE,
        ])
            ->assertUnprocessable()
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                ->where('message.text', trans('exceptions.transfer_payer_must_be_consumer'))
            );
    }
}
