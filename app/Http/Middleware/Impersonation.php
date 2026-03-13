<?php

namespace App\Http\Middleware;

use App\Enums\FeatureToggles;
use App\Enums\SessionVariables;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Impersonation {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if (toggleEnabled(FeatureToggles::IMPERSONATION) && session()->has(SessionVariables::IMPERSONATE->value) && Auth::check() && Auth::user()->hasRole('wm') || Auth::user()->hasRole('awm')) {
            session()->put(SessionVariables::IMPERSONATING_USER->value, Auth::id());
            Auth::onceUsingId(session(SessionVariables::IMPERSONATE->value));
        }

        return $next($request);
    }
}
