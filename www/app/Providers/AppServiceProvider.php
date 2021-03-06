<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (App::environment('production')) {
            URL::forceScheme('https');
        }

        Cashier::calculateTaxes();

        Blade::directive('price', function ($expression) {
            return '<?php 
                $params = ' . $expression . ';

                echo \App\Helpers\PriceHelper::price($params[\'price\'], $params[\'period\']);
            ?>';
        });
    }
}
