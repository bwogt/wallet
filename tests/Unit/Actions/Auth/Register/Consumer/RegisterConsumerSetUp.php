<?php

namespace Tests\Unit\Actions\Auth\Register\Consumer;

use App\Actions\Auth\Register\RegisterConsumerAction;
use App\Dto\Auth\Register\RegisterConsumerDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class RegisterConsumerSetUp extends TestCase
{
    use RefreshDatabase;

    protected $logger;
    protected RegisterConsumerAction $action;
    protected RegisterConsumerDTO $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loggerSetUp();
        $this->actionSetUp();
        $this->dataSetUp();
    }

    private function loggerSetUp(): void
    {
        $this->logger = Mockery::spy(LoggerInterface::class);
    }

    private function actionSetUp(): void
    {
        $this->action = new RegisterConsumerAction(
            app(\Illuminate\Database\DatabaseManager::class),
            $this->logger
        );
    }

    private function dataSetUp(): void
    {
        $faker = \Faker\Factory::create('pt_BR');

        $this->data = new RegisterConsumerDTO(
            name: $faker->name(),
            email: $faker->unique()->safeEmail(),
            password: 'password',
            cpf: $faker->cpf()
        );
    }
}
