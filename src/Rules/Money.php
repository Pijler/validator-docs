<?php

namespace ValidatorDocs\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use ValidatorDocs\Support\Helpers;
use ValidatorDocs\Traits\WithParameters;

class Money implements Rule
{
    use WithParameters;

    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        return $this->checkMoney($value);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return Helpers::getMessage('money');
    }

    /**
     * Determine if the money is valid.
     */
    private function checkMoney(mixed $value): bool
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
