<?php

namespace App\Enum\User;

enum UserType: string
{
    case CONSUMER = 'consumer';
    case MERCHANT = 'merchant';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isConsumer(): bool
    {
        return $this === self::CONSUMER;
    }

    public function isMerchant(): bool
    {
        return $this === self::MERCHANT;
    }
}