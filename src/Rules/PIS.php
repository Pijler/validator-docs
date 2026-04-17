<?php

namespace ValidatorDocs\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use ValidatorDocs\Support\Helpers;
use ValidatorDocs\Traits\WithParameters;

class PIS implements ValidationRule
{
    use WithParameters;

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->passes($value) === false) {
            $fail(Helpers::getMessage('pis'));
        }
    }

    /**
     * Determine if the validation rule passes.
     */
    protected function passes(mixed $value): bool
    {
        return $this->checkFormatted($value)
            && $this->checkPIS(Str::onlyNumbers($value));
    }

    /**
     * Check if the value is formatted.
     */
    private function checkFormatted(mixed $value): bool
    {
        if (! $this->hasFormat()) {
            return true;
        }

        return preg_match('/^\d{3}\.\d{5}\.\d{2}-\d{1}$/', $value) > 0;
    }

    /**
     * Determine if the PIS is valid.
     */
    private function checkPIS(mixed $p): bool
    {
        if (mb_strlen($p) != 11 || preg_match('/^'.$p[0].'{11}$/', $p)) {
            return false;
        }

        $multipliers = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $sum = 0;

        for ($position = 0; $position < 10; $position++) {
            $sum += (int) $p[$position] * $multipliers[$position];
        }

        $mod = $sum % 11;

        return (int) $p[10] === ($mod < 2 ? 0 : 11 - $mod);
    }
}
