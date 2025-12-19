<?php

namespace App\Actions\Auth\Consumer;

use App\Dto\Auth\Consumer\RegisterConsumerDTO;
use App\Dto\Auth\Login\LoginDTO;
use App\Exceptions\HttpJsonResponseException;
use App\Models\Consumer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
                $consumer = $this->createConsumer($user, $data);

                $this->createWallet($user);
                $this->logSuccess($consumer, $user);

                return new LoginDTO(
                    user: $user->load('consumer'),
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
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
    }

    private function createConsumer(User $user, RegisterConsumerDTO $data): Consumer
    {
        return Consumer::create([
            'user_id' => $user->id,
            'cpf' => $data->cpf,
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

    private function logSuccess(Consumer $consumer, User $user): void
    {
        $this->logger->info('Consumer registered successfully', [
            'consumer_id' => $consumer->id,
            'user_id' => $user->id,
            'name' => $user->name,
        ]);
    }

    private function handleException(\Exception $e, RegisterConsumerDTO $data): never
    {
        $this->logger->error('Consumer registration failed', [
            'name' => $data->name,
            'email' => $data->email,
            'name' => $data->name,
            'error' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ],
        ]);

        throw new HttpJsonResponseException(
            trans('auth.register.failed.consumer'),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
