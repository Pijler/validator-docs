<?php

namespace ValidatorDocs\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use ValidatorDocs\Support\Helpers;
use ValidatorDocs\Traits\WithParameters;

class Money implements ValidationRule
{
    use WithParameters;

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->passes($value) === false) {
            $fail(Helpers::getMessage('money'));
        }
    }

    /**
     * Determine if the validation rule passes.
     */
    protected function passes(mixed $value): bool
    {
        $money = Str::moneyValue($value);

        $money = Number::currency($money, $this->getCurrency(), $this->getLocale());

        return Str::of($money)->contains($value);
    }

    /**
     * Get the locale from callback or parameters.
     */
    private function getLocale(): mixed
    {
        return data_get($this->parameters, '1');
    }

    /**
     * Get the currency from callback or parameters.
     */
    private function getCurrency(): mixed
    {
        return data_get($this->parameters, '0', 'USD');
    }
}
