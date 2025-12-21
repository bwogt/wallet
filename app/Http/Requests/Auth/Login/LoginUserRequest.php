<?php

namespace App\Http\Requests\Auth\Login;

use App\Dto\Auth\Login\CredentialDTO;
use App\Http\Requests\Base\ApiFormRequest;

class LoginUserRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Convert the request data to a DTO.
     */
    public function toDTO(): CredentialDTO
    {
        return new CredentialDTO(
            email: $this->input('email'),
            password: $this->input('password')
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
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'max:255'],
        ];
    }
}
