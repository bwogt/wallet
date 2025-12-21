<?php

namespace Tests\Unit\Actions\Auth\Login;

use App\Dto\Auth\Login\LoginDTO;
use App\Exceptions\InvalidCredentialsException;

class LoginActionTest extends LoginActionTestSetUp
{
    public function test_should_return_an_instance_of_login_dto_when_registration_is_successful(): void
    {
        $login = ($this->action)($this->credentials());
        $this->assertInstanceOf(LoginDTO::class, $login);
    }

    public function test_should_generate_a_personal_access_token_for_the_user(): void
    {
        $login = ($this->action)($this->credentials());

        $this->assertNotEmpty($login->token);
        $this->assertCount(1, $this->user->tokens);
    }

    public function test_should_revoke_existing_tokens_before_creating_a_new_one(): void
    {
        $this->user->createToken('auth-token');
        $this->assertCount(1, $this->user->tokens);

        ($this->action)($this->credentials());
        $this->assertCount(1, $this->user->refresh()->tokens);
    }

    public function test_should_throw_an_invalid_credentials_exception_when_the_email_is_incorrect(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $this->expectExceptionMessage(trans('exceptions.invalid_credentials'));

        ($this->action)($this->credentials(email: fake()->safeEmail()));
    }

    public function test_should_throw_an_invalid_credentials_exception_when_the_password_is_incorrect(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $this->expectExceptionMessage(trans('exceptions.invalid_credentials'));

        ($this->action)($this->credentials(password: 'pass'));
    }
}
