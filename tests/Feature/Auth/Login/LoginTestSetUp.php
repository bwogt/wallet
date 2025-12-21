<?php

namespace Tests\Feature\Auth\Login;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $merchant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userSetUp();
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()->consumer()->create();
        $this->merchant = UserFactory::new()->merchant()->create();
    }

    protected function route(): string
    {
        return route('api.v1.auth.login');
    }
}
