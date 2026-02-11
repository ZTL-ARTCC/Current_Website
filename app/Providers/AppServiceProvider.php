<?php

namespace App\Providers;

use App\Enums\FeatureToggles;
use App\Enums\SessionVariables;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
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

        Blade::if('toggle', function ($toggle_enum) {
            return toggleEnabled($toggle_enum);
        });

        View::share('FeatureToggles', FeatureToggles::class);
        View::share('SessionVariables', SessionVariables::class);

        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page'): LengthAwarePaginator {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        Vite::macro('image', fn (string $asset) => $this->asset("resources/assets/img/{$asset}"));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void {
    }
}
