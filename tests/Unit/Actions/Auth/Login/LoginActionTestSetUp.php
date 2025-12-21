<?php

namespace Tests\Unit\Actions\Auth\Login;

use App\Actions\Auth\Login\LoginAction;
use App\Dto\Auth\Login\CredentialDTO;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected LoginAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userSetUp();
        $this->actionSetUp();
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()
            ->consumer()
            ->create(['password' => 'password']);
    }

    private function actionSetUp(): void
    {
        $this->action = new LoginAction;
    }

    protected function credentials(?string $email = null, ?string $password = null): CredentialDTO
    {
        return new CredentialDTO(
            email: $email ?? $this->user->email,
            password: $password ?? 'password'
        );
    }
}
