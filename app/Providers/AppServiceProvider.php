<?php

namespace App\Providers;

use App\Apis\Rentablo\Rentablo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('RentabloApi', function ($app, array $parameters) {

            $user = auth()->user();
            $service = $user->services()->where('slug', 'rentablo')->first();
            if (is_null($service)) {
                return null;
            }

            $service->uri = config('rentablo.uri');

            return new Rentablo($service);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('formatted_number', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9]+,?[0-9]*$/', $value);
        });

        Carbon::setLocale('de');
    }
}
