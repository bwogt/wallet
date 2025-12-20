<?php

namespace Tests\Unit\Actions\Auth\Register\Merchant;

use App\Actions\Auth\Register\RegisterMerchantAction;
use App\Dto\Auth\Register\RegisterMerchantDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterMerchantSetUp extends TestCase
{
    use RefreshDatabase;

    protected RegisterMerchantAction $action;
    protected RegisterMerchantDTO $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionSetUp();
        $this->dataSetUp();
    }

    protected function actionSetUp(): void
    {
        $this->action = new RegisterMerchantAction();
    }

    protected function dataSetUp(): void
    {
        $faker = \Faker\Factory::create('pt_BR');

        $this->data = new RegisterMerchantDTO(
            name: $faker->name(),
            email: $faker->unique()->safeEmail(),
            password: 'password',
            cnpj: $faker->cnpj()
        );
    }
}
