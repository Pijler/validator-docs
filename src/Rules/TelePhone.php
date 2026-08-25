<?php

namespace Pijler\ValidatorDocs\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Pijler\ValidatorDocs\Support\Helpers;

class TelePhone implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->passes($value) === false) {
            $fail(Helpers::getMessage('telephone'));
        }
    }

    /**
     * Determine if the validation rule passes.
     */
    protected function passes(mixed $value): bool
    {
        return preg_match('/^\d{4}-\d{4}$/', $value) > 0;
    }
}
