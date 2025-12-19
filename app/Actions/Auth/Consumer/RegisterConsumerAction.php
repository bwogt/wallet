<?php

namespace App\Actions\Auth\Consumer;

use App\Dto\Auth\Consumer\RegisterConsumerDTO;
use App\Dto\Auth\Login\LoginDTO;
use App\Enum\User\UserType;
use App\Exceptions\HttpJsonResponseException;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class RegisterConsumerAction
{
    public function __construct(
        private readonly \Illuminate\Database\DatabaseManager $db,
        private readonly \Psr\Log\LoggerInterface $logger
    ) {}

    public function __invoke(RegisterConsumerDTO $data): LoginDTO
    {
        try {
            return $this->db->transaction(function () use ($data) {
                $user = $this->createUser($data);
                $this->createWallet($user);
                $this->logSuccess($user);

                return new LoginDTO(
                    user: $user,
                    token: $this->createPersonalAccessToken($user)
                );
            });
        } catch (\Exception $e) {
            $this->handleException($e, $data);
        }
    }

    private function createUser(RegisterConsumerDTO $data): User
    {
        return User::create([
            'type' => UserType::CONSUMER,
            'name' => $data->name,
            'email' => $data->email,
            'cpf' => $data->cpf,
            'password' => $data->password,
        ]);
    }

    private function createWallet(User $user): void
    {
        $user->wallet()->create();
    }

    private function createPersonalAccessToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    private function logSuccess(User $user): void
    {
        $this->logger->info('User registered successfully', [
            'user_id' => $user->id,
            'type' => $user->type->value,
            'name' => $user->name,
        ]);
    }

    private function handleException(\Exception $e, RegisterConsumerDTO $data): never
    {
        $this->logger->error('User registration failed', [
            'name' => $data->name,
            'email' => $data->email,
            'error' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ],
        ]);

        throw new HttpJsonResponseException(
            trans('auth.register.failed'),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
