<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use App\Apis\Rentablo\Rentablo;
use App\Models\Services\Service;
use Illuminate\Support\Facades\Cache;
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

            $service = Cache::rememberForever('services.rentablo', function () {
                return Service::where('slug', 'rentablo')->first();
            });

            if (is_null($service)) {
                Cache::forget('services.rentablo');
                return null;
            }

            $service_user = \App\Models\Services\User::where('user_id', $user->id)
                ->where('service_id', $service->id)
                ->first();

            if (is_null($service_user)) {
                return null;
            }

            $service_user->uri = config('services.rentablo.base_uri');

            return new Rentablo($service_user);
        });

        $this->app->singleton('HabiticaApi', function ($app, array $parameters) {

            $user = auth()->user();

            $service = Cache::rememberForever('services.habitica', function () {
                return Service::where('slug', 'habitica')->first();
            });

            if (is_null($service)) {
                Cache::forget('services.habitica');
                return null;
            }

            $service_user = \App\Models\Services\User::where('user_id', $user->id)
                ->where('service_id', $service->id)
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
