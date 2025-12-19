<?php

namespace Tests\Feature\Auth\Register\Consumer;;

use App\Enum\FlashMessage\FlashMessageType;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

class RegisterConsumerRulesTest extends RegisterConsumerTestSetUp
{
    public function test_should_return_all_errors_when_the_required_fields_are_null_values(): void
    {
        $this->postJson($this->route())
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.name.0', trans('validation.required', [
                        'attribute' => trans('validation.attributes.name'),
                    ]))
                    ->where('errors.email.0', trans('validation.required', [
                        'attribute' => 'email',
                    ]))
                    ->where('errors.cpf.0', trans('validation.required', [
                        'attribute' => 'cpf',
                    ]))
                    ->where('errors.password.0', trans('validation.required', [
                        'attribute' => trans('validation.attributes.password'),
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_name_field_value_is_longer_than_255_characters(): void
    {
        $this->postJson($this->route(), $this->validUserData(['name' => Str::random(256)]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.name.0', trans('validation.max.string', [
                        'max' => 255,
                        'attribute' => trans('validation.attributes.name'),
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_email_field_value_is_not_a_valid_email(): void
    {
        $this->postJson($this->route(), $this->validUserData(['email' => Str::random(10)]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.email.0', trans('validation.email', [
                        'attribute' => 'email',
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_email_field_value_exists_in_the_database(): void
    {
        $this->postJson($this->route(), $this->validUserData(['email' => $this->user->email]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.email.0', trans('validation.unique', [
                        'attribute' => 'email',
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_cpf_field_values_exists_in_the_database(): void
    {
        $this->postJson($this->route(), $this->validUserData(['cpf' => $this->user->cpf]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.cpf.0', trans('validation.unique', [
                        'attribute' => 'cpf',
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_password_field_value_is_less_than_8_characters_longs(): void
    {
        $password = Str::random(7);

        $this->postJson($this->route(), $this->validUserData([
            'password' => $password,
            'password_confirmation' => $password,
        ]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.password.0', trans('validation.min.string', [
                        'attribute' => trans('validation.attributes.password'),
                        'min' => 8,
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_password_field_value_is_longer_than_255_characters(): void
    {
        $password = Str::random(256);

        $this->postJson($this->route(), $this->validUserData([
            'password' => $password,
            'password_confirmation' => $password,
        ]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.password.0', trans('validation.max.string', [
                        'attribute' => trans('validation.attributes.password'),
                        'max' => 255,
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_password_is_different_from_the_password_confirmation(): void
    {
        $this->postJson($this->route(), $this->validUserData(['password_confirmation' => Str::random(8)]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.password.0', trans('validation.confirmed', [
                        'attribute' => trans('validation.attributes.password'),
                    ]))
            );
    }
}
