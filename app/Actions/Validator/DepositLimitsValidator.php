<?php

namespace App\Actions\Validator;

use App\Constants\Deposit\DepositConstants;
use App\Exceptions\InvalidDepositAmountException;

class DepositLimitsValidator
{
    public function __construct(
        private readonly float $amount
    ) {}

    public static function check(float $amount): self
    {
        return new self($amount);
    }

    public function amountMustBeAboveMinimum(): self
    {
        $isInvalidAmount = $this->amount < DepositConstants::MIN_AMOUNT;

        throw_if($isInvalidAmount, new InvalidDepositAmountException(
            trans('exceptions.deposit_amount_below_minimum', [
                'minimum' => DepositConstants::MIN_AMOUNT,
            ])
        ));

        return $this;
    }

    public function amountMustBeLessThanMaximum(): self
    {
        $isInvalidAmount = $this->amount > DepositConstants::MAX_AMOUNT;

        throw_if($isInvalidAmount, new InvalidDepositAmountException(
            trans('exceptions.deposit_amount_above_maximum', [
                'maximum' => DepositConstants::MAX_AMOUNT,
            ])
        ));

        return $this;
    }
}
