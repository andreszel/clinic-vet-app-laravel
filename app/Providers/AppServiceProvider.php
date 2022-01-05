<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        // zmienne współdzielone, które przydatne są dla w wielu widokach
        // używamy ich bezpośrednio w widoku
        view()->share('appName', 'Clinic VET APP');
        View::share('appTitle', 'Panel administracyjny - Clinic VET APP');
        //Schema::defaultStringLength(191);
        //date_default_timezone_set('Europe/Warsaw');

        // add Str::currency macro
        Str::macro('currency', function ($price) {
            return number_format($price, 2, '.', ' ');
        });

        Blade::directive('currency_format', function ($money) {
            return "<?php echo number_format($money, 2, '.', ' '); ?>";
        });

        Paginator::useBootstrap();
    }
}
