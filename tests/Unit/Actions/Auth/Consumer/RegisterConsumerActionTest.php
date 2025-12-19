<?php

namespace Tests\Unit\Actions\Auth\Consumer;

use App\Actions\Auth\Consumer\RegisterConsumerAction;
use App\Dto\Auth\Login\LoginDTO;
use App\Exceptions\HttpJsonResponseException;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisterConsumerActionTest extends TestCase
{
    use RefreshDatabase, RegisterConsumerSetUp;

    protected function setUp(): void
    {
        parent::setUp();
        $this->prepareScenario();
    }

    public function test_should_return_an_instance_of_login_dto_when_registration_is_successful(): void
    {
        $result = ($this->action)($this->data);
        $this->assertInstanceOf(LoginDTO::class, $result);
    }

    public function test_should_create_a_new_user_in_the_database(): void
    {
        $result = ($this->action)($this->data);

        $this->assertDatabaseHas('users', [
            'id' => $result->user->id,
            'name' => $this->data->name,
            'email' => $this->data->email,
        ]);
    }

    public function test_should_create_a_new_consumer_in_the_database(): void
    {
        $result = ($this->action)($this->data);

        $this->assertDatabaseHas('consumers', [
            'id' => $result->user->consumer->id,
            'user_id' => $result->user->id,
            'cpf' => $this->data->cpf,
        ]);
    }

    public function test_should_create_a_wallet_for_the_new_user(): void
    {
        $result = ($this->action)($this->data);

        $this->assertDatabaseHas('wallets', [
            'user_id' => $result->user->id,
            'balance' => 0,
        ]);
    }

    public function test_should_create_a_wallet_with_zero_balance_for_the_new_user(): void
    {
        $result = ($this->action)($this->data);
        $this->assertEquals(0, $result->user->wallet->balance);
    }

    public function test_should_throw_an_exception_when_an_internal_server_error_occurs(): void
    {
        $this->expectException(HttpJsonResponseException::class);
        $this->expectExceptionCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        $this->expectExceptionMessage(trans('auth.register.failed.consumer'));

        $dbMock = Mockery::mock(\Illuminate\Database\DatabaseManager::class);
        $dbMock->shouldReceive('transaction')
            ->once()
            ->andThrow(new Exception('Simulates a DB error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            ));

        $action = new RegisterConsumerAction($dbMock, $this->logger);
        $action($this->data);
    }
}
