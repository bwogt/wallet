<?php

namespace App\Actions\Validator;

use App\Constants\Transfer\TransferConstants;
use App\Exceptions\InvalidTransferValueException;

class TransferLimitsValidator
{
    public function __construct(
        private readonly float $value
    ) {}

    public static function check(float $value): self
    {
        return new self($value);
    }

    public function valueMustBeAboveMinimum(): self
    {
        $isInvalidValue = $this->value < TransferConstants::MIN_VALUE;

        throw_if($isInvalidValue, new InvalidTransferValueException(
            trans('exceptions.transfer_value_below_minimum', [
                'minimum' => TransferConstants::MIN_VALUE,
            ])
        ));

        return $this;
    }

    public function valueMustBeLessThanMaximum(): self
    {
        $isInvalidValue = $this->value > TransferConstants::MAX_VALUE;

        throw_if($isInvalidValue, new InvalidTransferValueException(
            trans('exceptions.transfer_value_above_maximum', [
                'maximum' => TransferConstants::MAX_VALUE,
            ])
        ));

        return $this;
    }
}
