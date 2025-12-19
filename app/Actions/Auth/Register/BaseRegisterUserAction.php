<?php

namespace App\Actions\Auth\Register;

use App\Dto\Auth\Login\LoginDTO;
use App\Dto\Auth\Register\RegisterUserDTOInterface;
use App\Exceptions\HttpJsonResponseException;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseRegisterUserAction
{
    public function __construct(
        private readonly \Illuminate\Database\DatabaseManager $db,
        private readonly \Psr\Log\LoggerInterface $logger
    ) {}

    public function __invoke(RegisterUserDTOInterface $data): LoginDTO
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

    abstract protected function createUser(RegisterUserDTOInterface $data): User;

    protected function createWallet(User $user): void
    {
        $user->wallet()->create();
    }

    protected function createPersonalAccessToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    protected function logSuccess(User $user): void
    {
        $this->logger->info('User registered successfully', [
            'user_id' => $user->id,
            'type' => $user->type->value,
            'name' => $user->name,
        ]);
    }

    protected function handleException(\Exception $e, RegisterUserDTOInterface $data): never
    {
        $this->logger->error('User registration failed', [
            'name' => $data->getName(),
            'email' => $data->getEmail(),
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
