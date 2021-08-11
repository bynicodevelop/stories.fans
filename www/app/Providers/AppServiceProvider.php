<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        Cashier::calculateTaxes();

        Blade::directive('price', function ($expression) {
            return '<?php 
                $params = ' . $expression . ';

                echo \App\Helpers\PriceHelper::price($params[\'price\'], $params[\'period\']);
            ?>';
        });

        Blade::directive('premium', function ($expression) {
            return '<?php  
                if(\App\Helpers\PremiumHelper::isPremium(' . $expression . ')) {
            ?>';
        });

        Blade::directive('elsepremium', function ($expression) {
            return '<?php } else { ?>';
        });

        Blade::directive('endpremium', function ($expression) {
            return '<?php } ?>';
        });
    }
}
