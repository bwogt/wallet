<?php

namespace App\Http\Resources\Transaction\Transfer;

use App\Http\Resources\Transaction\TransactionResource;
use App\Http\Resources\User\UserBaseResource;
use Illuminate\Http\Request;

class TransferResource extends TransactionResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $parent = parent::toArray($request);

        return array_merge($parent, [
            'payer' => UserBaseResource::make($this->payer),
            'payee' => UserBaseResource::make($this->payee),
        ]);
    }
}
