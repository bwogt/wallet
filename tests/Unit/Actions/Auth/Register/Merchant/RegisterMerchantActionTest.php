<?php

namespace Tests\Unit\Actions\Auth\Register\Merchant;

use App\Enum\User\UserType;

class RegisterMerchantActionTest extends RegisterMerchantSetUp
{
    public function test_should_create_a_new_consumer_user_in_the_database(): void
    {
        $result = ($this->action)($this->data);

        $this->assertDatabaseHas('users', [
            'id' => $result->user->id,
            'type' => UserType::MERCHANT->value,
            'name' => $this->data->name,
            'email' => $this->data->email,
            'cpf' => null,
            'cnpj' => $this->data->cnpj,
        ]);
    }
}
