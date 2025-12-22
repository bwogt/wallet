<?php

namespace Tests\Feature\Transactions\Deposit;

use App\Enum\FlashMessage\FlashMessageType;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class DepositRulesTest extends DepositTestSetUp
{
    public function test_should_return_an_error_when_the_value_field_are_null(): void
    {
        Sanctum::actingAs($this->consumer);

        $this->postJson($this->route())
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors.form_request'))
                    ->where('errors.value.0', trans('validation.required', [
                        'attribute' => trans('validation.attributes.value'),
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_value_field_is_not_numeric(): void
    {
        Sanctum::actingAs($this->consumer);

        $this->postJson($this->route(), ['value' => '100.0a'])
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors.form_request'))
                    ->where('errors.value.0', trans('validation.numeric', [
                        'attribute' => trans('validation.attributes.value'),
                    ]))
            );
    }
}
