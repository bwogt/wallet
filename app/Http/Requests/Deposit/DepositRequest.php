<?php

namespace App\Http\Requests\Deposit;

use App\Dto\Auth\Transaction\DepositDTO;
use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest
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
    public function toDTO(): DepositDTO
    {
        return new DepositDTO(
            user_id: $this->user()->id,
            amount: $this->amount
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
            'amount' => ['required', 'numeric'],
        ];
    }
}
