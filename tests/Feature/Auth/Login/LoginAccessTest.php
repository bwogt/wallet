<?php

namespace Tests\Feature\Auth\Login;

use App\Enum\FlashMessage\FlashMessageType;
use App\Enum\User\UserType;
use Illuminate\Testing\Fluent\AssertableJson;

class LoginAccessTest extends LoginTestSetUp
{
    public function test_a_consumer_user_should_be_able_to_login(): void
    {
        $this->postJson($this->route(), [
            'email' => $this->user->email,
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::SUCCESS)
                ->where('message.text', trans('flash_messages.success.login'))
                ->where('data.user.id', $this->user->id)
                ->where('data.user.type', UserType::CONSUMER->value)
                ->where('data.user.name', $this->user->name)
                ->where('data.user.email', $this->user->email)
                ->where('data.user.cpf', $this->user->cpf)
                ->has('data.token')
                ->missing('data.user.cnpj')
                ->missing('data.user.password')
            );
    }

    public function test_a_merchant_user_should_be_able_to_login(): void
    {
        $this->postJson($this->route(), [
            'email' => $this->merchant->email,
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::SUCCESS)
                ->where('message.text', trans('flash_messages.success.login'))
                ->where('data.user.id', $this->merchant->id)
                ->where('data.user.type', UserType::MERCHANT->value)
                ->where('data.user.name', $this->merchant->name)
                ->where('data.user.email', $this->merchant->email)
                ->where('data.user.cnpj', $this->merchant->cnpj)
                ->has('data.token')
                ->missing('data.user.cpf')
                ->missing('data.user.password')
            );
    }
}
