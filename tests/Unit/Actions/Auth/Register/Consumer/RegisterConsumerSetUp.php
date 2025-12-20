<?php

namespace Tests\Unit\Actions\Auth\Register\Consumer;

use App\Actions\Auth\Register\RegisterConsumerAction;
use App\Dto\Auth\Register\RegisterConsumerDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterConsumerSetUp extends TestCase
{
    use RefreshDatabase;

    protected RegisterConsumerAction $action;
    protected RegisterConsumerDTO $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionSetUp();
        $this->dataSetUp();
    }

    private function actionSetUp(): void
    {
        $this->action = new RegisterConsumerAction();
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
