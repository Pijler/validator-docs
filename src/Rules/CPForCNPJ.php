<?php

namespace ValidatorDocs\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use ValidatorDocs\Support\Helpers;
use ValidatorDocs\Traits\WithParameters;

class CPForCNPJ implements ValidationRule
{
    use WithParameters;

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->passes($attribute, $value) === false) {
            $fail(Helpers::getMessage('cpf_or_cnpj'));
        }
    }

    /**
     * Determine if the validation rule passes.
     */
    protected function passes(string $attribute, mixed $value): bool
    {
        $cpfPasses = true;
        (new CPF)->parameters($this->parameters)->validate($attribute, $value, function () use (&$cpfPasses): void {
            $cpfPasses = false;
        });

        if ($cpfPasses) {
            return true;
        }

        $cnpjPasses = true;
        (new CNPJ)->parameters($this->parameters)->validate($attribute, $value, function () use (&$cnpjPasses): void {
            $cnpjPasses = false;
        });

        return $cnpjPasses;
    }
}
