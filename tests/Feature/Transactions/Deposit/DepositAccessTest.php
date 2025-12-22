<?php

namespace Tests\Feature\Transactions\Deposit;

use App\Constants\Deposit\DepositConstants;
use App\Enum\FlashMessage\FlashMessageType;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class DepositAccessTest extends DepositTestSetUp
{
    public function test_an_unauthenticated_user_should_not_be_able_to_deposit(): void
    {
        $this->postJson($this->route(), ['value' => DepositConstants::MIN_VALUE])
            ->assertUnauthorized()
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                ->where('message.text', trans('http_exceptions.unauthenticated'))
            );
    }

    public function test_a_consumer_user_should_be_able_to_deposit(): void
    {
        Sanctum::actingAs($this->consumer);

        $this->postJson($this->route(), ['value' => DepositConstants::MIN_VALUE])
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::SUCCESS)
                ->where('message.text', trans('flash_messages.success.deposit'))
            );
    }

    public function test_a_merchant_user_should_be_able_to_deposit(): void
    {
        Sanctum::actingAs($this->merchant);

        $this->postJson($this->route(), ['value' => DepositConstants::MIN_VALUE])
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::SUCCESS)
                ->where('message.text', trans('flash_messages.success.deposit'))
            );
    }
}
