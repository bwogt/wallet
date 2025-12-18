<?php

namespace Tests\Unit\Actions\Auth\Consumer;

use App\Actions\Auth\Consumer\RegisterConsumerAction;
use App\Dto\Auth\Consumer\RegisterConsumerDTO;
use Mockery;
use Psr\Log\LoggerInterface;

trait RegisterConsumerSetUp
{
    protected $logger;
    protected RegisterConsumerAction $action;
    protected RegisterConsumerDTO $data;

    protected function prepareScenario(): void
    {
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
            name:  $faker->name(),
            email: $faker->unique()->safeEmail(),
            password: 'password',
            cpf: $faker->cpf()
        );
    }
}