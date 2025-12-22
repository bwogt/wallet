<?php

namespace App\Actions\Validator;

use App\Exceptions\InvalidPayerTypeException;
use App\Models\User;

class TransferPayerValidator
{
    public function __construct(private readonly User $payer) {}

    public static function for(User $payer): self
    {
        return new self($payer);
    }

    public function mustBeConsumer(): self
    {
        throw_unless($this->payer->type->isConsumer(), 
            new InvalidPayerTypeException(trans('exceptions.transfer_payer_must_be_consumer')));

        return $this;
    }
}