<?php

namespace ValidatorDocs\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use ValidatorDocs\Support\Helpers;
use ValidatorDocs\Traits\WithParameters;

class CNPJ implements ValidationRule
{
    use WithParameters;

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->passes($value) === false) {
            $fail(Helpers::getMessage('cnpj'));
        }
    }

    /**
     * Determine if the validation rule passes.
     */
    protected function passes(mixed $value): bool
    {
        return $this->checkFormatted($value)
            && $this->checkCNPJ(Str::onlyNumbers($value));
    }

    /**
     * Check if the value is formatted.
     */
    private function checkFormatted(mixed $value): bool
    {
        if (! $this->hasFormat()) {
            return true;
        }

        return preg_match('/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/', $value) > 0;
    }

    /**
     * Check if the CNPJ is valid.
     */
    private function checkCNPJ(mixed $c): bool
    {
        $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        if (strlen($c) != 14) {
            return false;
        } elseif (preg_match("/^{$c[0]}{14}$/", $c) > 0) {
            return false;
        }

        for ($i = 0, $n = 0; $i < 12; $n += $c[$i] * $b[++$i]);

        if ($c[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($i = 0, $n = 0; $i <= 12; $n += $c[$i] * $b[$i++]);

        if ($c[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }
}
