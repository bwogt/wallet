<?php

namespace Tests\Feature\Auth\Register\Consumer;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterConsumerTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::new()->consumer()->create();
    }

    protected function route(): string
    {
        return route('api.v1.auth.consumer.register');
    }

    protected function validUserData(array $overrides = []): array
    {
        $faker = \Faker\Factory::create('pt_BR');

        return array_merge([
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'cpf' => $faker->unique()->cpf(),
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $overrides);
    }
}
