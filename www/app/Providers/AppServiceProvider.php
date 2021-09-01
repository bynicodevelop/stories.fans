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

        Blade::directive('premium', function ($expression) {
            return '<?php  

                if($this->isPremium(' . $expression . ')) {
            ?>';
        });

        Blade::directive('elsepremium', function ($expression) {
            return '<?php } else { ?>';
        });

        Blade::directive('endpremium', function ($expression) {
            return '<?php } ?>';
        });

        Blade::directive('protectedcontent', function ($expression) {
            return '<?php  
                if(\App\Helpers\PremiumHelper::protectedContent(' . $expression . ')) {
            ?>';
        });

        Blade::directive('elseprotectedcontent', function ($expression) {
            return '<?php } else { ?>';
        });

        Blade::directive('endprotectedcontent', function ($expression) {
            return '<?php } ?>';
        });
    }
}
