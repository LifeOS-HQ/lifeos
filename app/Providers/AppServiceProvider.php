<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use App\Apis\Rentablo\Rentablo;
use App\Models\Services\Service;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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

        $this->app->singleton('HabiticaApi', function ($app, array $parameters) {

            $user = auth()->user();

            $habitica_service = Service::where('slug', 'habitica')->first();

            $service_user = \App\Models\Services\User::where('user_id', $user->id)
                ->where('service_id', $habitica_service->id)
                ->first();

            if (is_null($service_user)) {
                return null;
            }

            return new \App\Apis\Habitica\Habitica($service_user);
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
