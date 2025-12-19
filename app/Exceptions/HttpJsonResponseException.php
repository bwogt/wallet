<?php

namespace App\Exceptions;

use App\Enum\FlashMessage\FlashMessageType;
use Exception;
use Illuminate\Http\JsonResponse;

class HttpJsonResponseException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(): JsonResponse
    {
        return new JsonResponse(['message' => [
            'type' => FlashMessageType::ERROR->value,
            'text' => $this->getMessage(),
        ], ], $this->getCode());
    }
}