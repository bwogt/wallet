<?php

namespace App\Actions\Auth\Consumer;

use App\Exceptions\HttpJsonResponseException;
use App\Models\Consumer;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RegisterConsumerAction
{
    public function __construct(
        private readonly string $name, 
        private readonly string $email, 
        private readonly string $password,
        private readonly string $cpf
    ){}

    public function execute(): Consumer
    {
       try{
           return DB::transaction(function (){
                $user = $this->createUser();
                $consumer = $this->createConsumer($user);
                $this->logSuccess($consumer);

                return $consumer;
            });
       }catch(Exception $e){
            $this->handleException($e);
       }
    }

    private function createUser(): User
    {
        return User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
    }

    private function createConsumer(User $user): Consumer
    {
        return Consumer::create([
            'user_id' => $user->id,
            'cpf' => $this->cpf,
        ]);
    }

    private function logSuccess(Consumer $consumer): void
    {
        Log::info('Consumer registered successfully', [
            'consumer_id' => $consumer->id,
            'user_id' => $consumer->user_id,
            'cpf' => $consumer->cpf,
        ]);
    }

    private function handleException(Exception $e): never
    {
        Log::error('Consumer registration failed', [
            'name' => $this->name,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'error' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ],
        ]);

        throw new HttpJsonResponseException(
            trans('auth.register.failed.consumer'), 
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}