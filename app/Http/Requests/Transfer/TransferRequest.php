<?php

namespace App\Http\Requests\Transfer;

use App\Dto\Transaction\Transfer\TransferDTO;
use App\Http\Requests\Base\ApiFormRequest;

class TransferRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function toDTO(): TransferDTO
    {
        return new TransferDTO(
            payer_id: $this->user()->id,
            payee_id: $this->input('payee_id'),
            value: $this->input('value'),
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
            'payee_id' => ['required', 'exists:users,id'],
            'value' => ['required', 'numeric'],
        ];
    }
}
