<?php

namespace App\Http\Requests\Auth\Register;

use App\Dto\Auth\Register\RegisterMerchantDTO;
use App\Http\Requests\Base\ApiFormRequest;
use App\Rules\Cnpj;
use Illuminate\Validation\Rules;

class RegisterMerchantRequest extends ApiFormRequest
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
    public function toDTO()
    {
        return new RegisterMerchantDTO(
            name: $this->input('name'),
            email: $this->input('email'),
            password: $this->input('password'),
            cnpj: $this->input('cnpj'),
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
            'cnpj' => ['required', 'size:18', new Cnpj, 'unique:users,cnpj'],
        ];
    }
}
