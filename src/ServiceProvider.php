<?php

namespace ValidatorDocs;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use ValidatorDocs\Support\Macros;

/**
 * This pack was inspired by these packs:
 *
 * @see https://github.com/geekcom/validator-docs
 * @see https://github.com/LaravelLegends/pt-br-validator
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Macros::boot();

        $this->bootRules();

        $this->bootTranslations();
    }

    /**
     * Get the file path in the src directory.
     */
    private function srcDir(string $path): string
    {
        return __DIR__."/../{$path}";
    }

    /**
     * Get the Rule Instance.
     */
    private static function getRule($rule, $parameters): mixed
    {
        return property_exists($rule, 'parameters') ? $rule->parameters($parameters) : $rule;
    }

    /**
     * Boot the package translations.
     */
    private function bootTranslations(): void
    {
        $this->loadTranslationsFrom($this->srcDir('lang'), 'docs');

        $this->publishes([$this->srcDir('lang') => $this->app->langPath()], 'validator-docs');
    }

    /**
     * Boot the Rules in the application.
     */
    private function bootRules(): void
    {
        $rules = $this->getRules();

        $rules->each(function ($class, $name) {
            $rule = new $class;

            $extension = static function ($attribute, $value, $parameters) use ($rule) {
                $rule = static::getRule($rule, $parameters);

                return $rule->passes($attribute, $value);
            };

            $this->app['validator']->extend($name, $extension);

            $this->app['validator']->replacer($name, fn () => $rule->message());
        });
    }

    /**
     * Get the Rules.
     */
    private function getRules(): Collection
    {
        return collect([
            'uf' => Rules\UF::class,
            'cep' => Rules\CEP::class,
            'cnh' => Rules\CNH::class,
            'cns' => Rules\CNS::class,
            'cpf' => Rules\CPF::class,
            'pis' => Rules\PIS::class,
            'cnpj' => Rules\CNPJ::class,
            'money' => Rules\Money::class,
            'cellphone' => Rules\CellPhone::class,
            'telephone' => Rules\TelePhone::class,
            'cpf_or_cnpj' => Rules\CPForCNPJ::class,
            'vehicle_plate' => Rules\VehiclePlate::class,
            'cellphone_with_ddd' => Rules\CellPhoneWithDDD::class,
            'telephone_with_ddd' => Rules\TelePhoneWithDDD::class,
            'cellphone_with_code' => Rules\CellPhoneWithCode::class,
            'telephone_with_code' => Rules\TelePhoneWithCode::class,
            'cellphone_with_code_no_mask' => Rules\CellPhoneWithCodeNoMask::class,
        ]);
    }
}
