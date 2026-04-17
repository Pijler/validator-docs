<?php

namespace ValidatorDocs\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use ValidatorDocs\Support\Helpers;
use ValidatorDocs\Traits\WithParameters;

class CPF implements ValidationRule
{
    use WithParameters;

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->passes($value) === false) {
            $fail(Helpers::getMessage('cpf'));
        }
    }

    /**
     * Determine if the validation rule passes.
     */
    protected function passes(mixed $value): bool
    {
        return $this->checkFormatted($value)
            && $this->checkCPF(Str::onlyNumbers($value));
    }

    /**
     * Check if the value is formatted.
     */
    private function checkFormatted(mixed $value): bool
    {
        if (! $this->hasFormat()) {
            return true;
        }

        return preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $value) > 0;
    }

    /**
     * Determine if the CPF is valid.
     */
    private function checkCPF(mixed $c): bool
    {
        if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
            return false;
        }

        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);

        if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);

        if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }
}
