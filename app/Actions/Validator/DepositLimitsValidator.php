<?php

namespace App\Actions\Validator;

use App\Constants\Deposit\DepositConstants;
use App\Exceptions\InvalidDepositAmountException;

class DepositLimitsValidator
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
        $isInvalidValue = $this->value < DepositConstants::MIN_VALUE;

        throw_if($isInvalidValue, new InvalidDepositAmountException(
            trans('exceptions.deposit_value_below_minimum', [
                'minimum' => DepositConstants::MIN_VALUE,
            ])
        ));

        return $this;
    }

    public function valueMustBeLessThanMaximum(): self
    {
        $isInvalidValue = $this->value > DepositConstants::MAX_VALUE;

        throw_if($isInvalidValue, new InvalidDepositAmountException(
            trans('exceptions.deposit_value_above_maximum', [
                'maximum' => DepositConstants::MAX_VALUE,
            ])
        ));

        return $this;
    }
}
