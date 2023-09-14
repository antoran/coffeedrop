<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Validator;

class RuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->postcode();
    }

    public function postcode()
    {
        Validator::extend('postcode', function ($attribute, $value, $parameters, $validator): bool {
            return str($value)->replace(' ', '')->upper()->isMatch('/^[A-Z]{1,2}[0-9]{1,2}[A-Z]?[0-9][A-Z]{2}$/');
        });
    }
}
