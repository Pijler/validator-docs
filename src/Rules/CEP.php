<?php

namespace ValidatorDocs\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use ValidatorDocs\Support\Helpers;

class CEP implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->passes($value) === false) {
            $fail(Helpers::getMessage('cep'));
        }
    }

    /**
     * Determine if the validation rule passes.
     */
    protected function passes(mixed $value): bool
    {
        return preg_match('/^\d{2}\.?\d{3}-\d{3}$/', $value) > 0;
    }
}
