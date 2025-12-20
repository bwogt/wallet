<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cpf implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = $this->sanitize($value);

        if (! $this->hasValidLength($cpf)) {
            $fail(trans('validation.custom.cpf.format'));

            return;
        }

        if ($this->isRepeatedDigits($cpf)) {
            $fail(trans('validation.custom.cpf.sequential'));

            return;
        }

        if (! $this->hasValidVerifiers($cpf)) {
            $fail(trans('validation.custom.cpf.invalid'));
        }
    }

    /**
     * Removes all non-digit characters from the given CPF.
     */
    private function sanitize(mixed $value): string
    {
        return preg_replace('/\D/', '', $value);
    }

    /**
     * Checks if the CPF is 11 characters long.
     */
    private function hasValidLength(string $cpf): bool
    {
        return strlen($cpf) === 11;
    }

    /**
     * Checks if the CPF contains only repeated digits (i.e. 11111111111).
     */
    private function isRepeatedDigits(string $cpf): bool
    {
        return preg_match('/^(\d)\1{10}$/', $cpf) === 1;
    }

    /**
     * Checks if the CPF's verifier digits are valid.
     */
    private function hasValidVerifiers(string $cpf): bool
    {
        $base = substr($cpf, 0, 9); // ignore check digits

        $digit1 = $this->calculateVerifierDigit($base); // expected first check digit
        $digit2 = $this->calculateVerifierDigit($base . $digit1); // expected second check digit

        return ($cpf[9] == $digit1) && ($cpf[10] == $digit2); // check digits
    }

    /**
     * Calculates the verifier digit of a CPF.
     */
    private function calculateVerifierDigit(string $digits): int
    {
        $size = strlen($digits);
        $weight = $size + 1;
        $sum = 0;

        for ($i = 0; $i < $size; $i++) {
            $sum += $digits[$i] * $weight--;
        }

        $remainder = ($sum * 10) % 11;

        return $remainder === 10 ? 0 : $remainder;
    }
}
