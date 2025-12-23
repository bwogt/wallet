<?php

namespace App\Actions\Transfer;

use App\Exceptions\Transfer\TransferUnauthorizedException;
use Illuminate\Support\Facades\Http;

class AuthorizeTransferAction
{
    public function __invoke(): void
    {
        try {
            if (! $this->isAuthorized()) {
                throw new TransferUnauthorizedException(
                    trans('exceptions.transfer_unauthorized')
                );
            }
        } catch (\Throwable) {
            throw new TransferUnauthorizedException(
                trans('exceptions.transfer_unauthorized')
            );
        }
    }

    private function isAuthorized(): bool
    {
        $response = Http::timeout(5)
            ->get(config('services.transfer_authorization.url'));

        return $response->ok()
            && $response->json('data.authorization') === true;
    }
}
