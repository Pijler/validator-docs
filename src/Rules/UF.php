<?php

namespace Pijler\ValidatorDocs\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Pijler\ValidatorDocs\Enum\StateEnum;
use Pijler\ValidatorDocs\Support\Helpers;

class UF implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->passes($value) === false) {
            $fail(Helpers::getMessage('uf'));
        }
    }

    /**
     * Determine if the validation rule passes.
     */
    protected function passes(mixed $value): bool
    {
        return collect(StateEnum::cases())->pluck('value')->contains($value);
    }
}
