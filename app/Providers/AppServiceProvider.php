<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();

        Blade::if('toggle', function ($toggle_name) {
            return toggleEnabled($toggle_name);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void {
        //
    }
}
