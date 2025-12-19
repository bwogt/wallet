<?php

namespace Tests\Unit\Actions\Auth\Register\Merchant;

use App\Actions\Auth\Register\RegisterMerchantAction;
use App\Dto\Auth\Register\RegisterMerchantDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class RegisterMerchantSetUp extends TestCase
{
    use RefreshDatabase;

    protected $logger;
    protected RegisterMerchantAction $action;
    protected RegisterMerchantDTO $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loggerSetUp();
        $this->actionSetUp();
        $this->dataSetUp();
    }

    protected function loggerSetUp(): void
    {
        $this->logger = Mockery::spy(LoggerInterface::class);
    }

    protected function actionSetUp(): void
    {
        $this->action = new RegisterMerchantAction(
            app(\Illuminate\Database\DatabaseManager::class),
            $this->logger
        );
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
