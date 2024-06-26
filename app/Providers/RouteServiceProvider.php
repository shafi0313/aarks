<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware(['2fa', 'web', 'blockCountry'])
                ->prefix('client/sales')
                ->group(base_path('routes/sales.php'));

            Route::middleware(['2fa', 'web', 'blockCountry'])
                ->prefix('client/purchase')
                ->group(base_path('routes/purchase.php'));

            Route::middleware(['2fa', 'web', 'blockCountry'])
                ->group(base_path('routes/admin.php'));

            Route::middleware(['2fa', 'web', 'blockCountry'])
                ->prefix('client')
                ->group(base_path('routes/web.php'));

            Route::middleware(['2fa', 'web', 'blockCountry'])
                ->prefix('client/calendar')
                ->group(base_path('routes/calendar.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
