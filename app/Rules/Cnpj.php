<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cnpj = $this->sanitize($value);

        if (! $this->hasValidLength($cnpj)) {
            $fail(trans('validation.custom.cnpj.format'));

            return;
        }

        if ($this->isRepeatedDigits($cnpj)) {
            $fail(trans('validation.custom.cnpj.sequential'));

            return;
        }

        if (! $this->hasValidVerifiers($cnpj)) {
            $fail(trans('validation.custom.cnpj.invalid'));
        }
    }

    /**
     * Removes all non-digit characters from the given CNPJ.
     */
    private function sanitize(mixed $value): string
    {
        return preg_replace('/\D/', '', $value);
    }

    /**
     * Checks if the CNPJ is 14 characters long.
     */
    private function hasValidLength(string $cnpj): bool
    {
        return strlen($cnpj) === 14;
    }

    /**
     * Checks if the CNPJ contains only repeated digits (i.e. 00000000000000).
     */
    private function isRepeatedDigits(string $cnpj): bool
    {
        return preg_match('/^(\d)\1{13}$/', $cnpj) === 1;
    }

    /**
     * Checks if the CNPJ's verifier digits are valid.
     */
    private function hasValidVerifiers(string $cnpj): bool
    {
        $base = substr($cnpj, 0, 12); // ignore check digits

        $digit1 = $this->calculateVerifierDigit($base); // expected first check digit
        $digit2 = $this->calculateVerifierDigit($base . $digit1); // expected second check digit

        return ($cnpj[12] == $digit1) && ($cnpj[13] == $digit2); // check digits
    }

    /**
     * Calculates the verifier digit of a CNPJ.
     */
    private function calculateVerifierDigit(string $digits): int
    {
        $weights = match (strlen($digits)) {
            12 => [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2],
            13 => [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2],
            default => [],
        };

        $sum = 0;

        foreach (str_split($digits) as $i => $digit) {
            $sum += (int) $digit * $weights[$i];
        }

        $remainder = $sum % 11;

        return $remainder < 2 ? 0 : 11 - $remainder;
    }
}
