<?php

namespace Tests\Feature\Auth\Register\Merchant;

use App\Enum\FlashMessage\FlashMessageType;
use Illuminate\Testing\Fluent\AssertableJson;

class RegisterMerchantAccessTest extends RegisterMerchantTestSetUp
{
    public function test_should_allow_unauthenticated_user_to_register(): void
    {
        $data = $this->validUserData();

        $this->postJson($this->route(), $data)
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::SUCCESS)
                ->where('message.text', trans_choice('flash_messages.success.registered.m', 1, [
                    'model' => trans_choice('model.user', 1),
                ]))
                ->where('data.user.name', $data['name'])
                ->where('data.user.email', $data['email'])
                ->where('data.user.cnpj', $data['cnpj'])
                ->has('data.user.created_at')
                ->has('data.user.updated_at')
                ->has('data.token')
                ->missing('data.user.password')
            );
    }
}
