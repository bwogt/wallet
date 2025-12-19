<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\User\UserBaseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => UserBaseResource::make($this->user),
            'token' => $this->token,
        ];
    }
}
