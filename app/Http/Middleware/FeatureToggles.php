<?php

namespace App\Http\Middleware;

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
    public function handle(Request $request, Closure $next, String $toggle_name) {
        if (! FeatureToggle::isEnabled($toggle_name)) {
            abort(404, 'Not found');
        }

        return $next($request);
    }
}
