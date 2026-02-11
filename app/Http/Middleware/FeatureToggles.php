<?php

namespace App\Http\Middleware;

use App\Enums\FeatureToggles as EnumsFeatureToggles;
use App\FeatureToggle;
use Closure;
use Illuminate\Http\Request;

class FeatureToggles {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $toggle_enum_case) {
        if (! FeatureToggle::isEnabled(EnumsFeatureToggles::from($toggle_enum_case))) {
            abort(404, 'Not found');
        }

        return $next($request);
    }
}
