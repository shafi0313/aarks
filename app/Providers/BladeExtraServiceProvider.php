<?php

namespace App\Providers;

use App\Models\HelpDesk;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeExtraServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('frontend.layout.menu.help', function ($view) {
            $view->with('helps', HelpDesk::with('subCategories')->whereParentId(0)->get());
        });
        Blade::if('impersonate', function () {
            if (session()->has('impersonate')) {
                return true;
            }
            return false;
        });
    }
}
