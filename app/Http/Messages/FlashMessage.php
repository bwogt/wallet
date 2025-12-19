<?php

namespace App\Http\Messages;

use App\Enum\FlashMessage\FlashMessageType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlashMessage extends JsonResource
{
    public function __construct(private FlashMessageType $type, private string $msg) {}

    /**
     * Merge data for the FlashMessage instance.
     */
    public function merge($data = [])
    {
        return array_merge($this->toArray(), $data);
    }

    /**
     * Convert the FlashMessage instance to an array.
     */
    public function toArray(?Request $request = null): array
    {
        return [
            'message' => [
                'type' => $this->type,
                'text' => $this->msg,
            ],
        ];
    }

    /**
     * Create a new FlashMessage instance with a success type.
     */
    public static function success(string $msg): self
    {
        return new self(type: FlashMessageType::SUCCESS, msg: $msg);
    }

    /**
     * Create a new FlashMessage instance with an info type.
     */
    public static function info(string $msg): self
    {
        return new self(type: FlashMessageType::INFO, msg: $msg);
    }

    /**
     * Create a new FlashMessage instance with a warning type.
     */
    public static function warning(string $msg): self
    {
        return new self(type: FlashMessageType::WARNING, msg: $msg);
    }

    /**
     * Create a new FlashMessage instance with an error type.
     */
    public static function error(string $msg): self
    {
        return new self(type: FlashMessageType::ERROR, msg: $msg);
    }
}
