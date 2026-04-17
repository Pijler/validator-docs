<?php

namespace ValidatorDocs\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use ValidatorDocs\Support\Helpers;

class VehiclePlate implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->passes($value) === false) {
            $fail(Helpers::getMessage('vehicle_plate'));
        }
    }

    /**
     * Determine if the validation rule passes.
     */
    protected function passes(mixed $value): bool
    {
        return preg_match('/^[a-zA-Z]{3}\-?[0-9][0-9a-zA-Z][0-9]{2}$/', $value) > 0;
    }
}
