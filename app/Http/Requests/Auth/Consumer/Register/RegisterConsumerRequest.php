<?php

namespace App\Http\Requests\Auth\Consumer\Register;

use App\Dto\Auth\Consumer\RegisterConsumerDTO;
use App\Http\Requests\Base\ApiFormRequest;
use Illuminate\Validation\Rules;

class RegisterConsumerRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function toDTO(): RegisterConsumerDTO
    {
        return new RegisterConsumerDTO(
            name: $this->input('name'),
            email: $this->input('email'),
            password: $this->input('password'),
            cpf: $this->input('cpf'),
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'max:255', Rules\Password::defaults()],
            'cpf' => ['required', 'size:14', 'unique:users,cpf'],
        ];
    }
}
