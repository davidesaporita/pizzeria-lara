<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Added for Heroku ClearDB needs
        Schema::defaultStringLength(191);

        // Force HTTPS when in production
        if(env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }

        // Blade custom directives
        Blade::directive('isMerchant', function() {
            return "<?php if(Auth::user()->isMerchant()): ?>";
        });

        Blade::directive('endisMerchant', function() {
            return "<?php endif; ?>";
        });

        Blade::directive('isCustomer', function() {
            return "<?php if(Auth::user()->isCustomer()): ?>";
        });

        Blade::directive('endisCustomer', function() {
            return "<?php endif; ?>";
        });        
    }
}
