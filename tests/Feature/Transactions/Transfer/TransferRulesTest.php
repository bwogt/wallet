<?php

namespace Tests\Feature\Transactions\Transfer;

use App\Enum\FlashMessage\FlashMessageType;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class TransferRulesTest extends TransferTestSetUp
{
    public function test_should_return_an_error_when_the_required_fields_is_null(): void
    {
        Sanctum::actingAs($this->payer);

        $this->postJson($this->route())
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors.form_request'))
                    ->where('errors.payee_id.0', trans('validation.required', [
                        'attribute' => trans('validation.attributes.payee_id'),
                    ]))
                    ->where('errors.value.0', trans('validation.required', [
                        'attribute' => trans('validation.attributes.value'),
                    ]))
            );
    }

     public function test_should_return_an_error_when_the_payee_id_field_does_not_exist(): void
    {
        Sanctum::actingAs($this->payer);

        $this->postJson($this->route(), [
            'payee_id' => '0',
            'value' => '999.99',
        ])
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors.form_request'))
                    ->where('errors.payee_id.0', trans('validation.exists', [
                        'attribute' => trans('validation.attributes.payee_id'),
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_value_field_is_not_numeric(): void
    {
        Sanctum::actingAs($this->payer, [
            'payee_id' => $this->payee->id,
            'value' => '999.bc',
        ]);

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
