<?php

namespace App\Http\Middleware;

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
        if (toggleEnabled('impersonation') && session()->has('impersonate') && Auth::user()->isAbleTo('snrStaff')) {
            session()->put('impersonating_user', Auth::id());
            Auth::onceUsingId(session('impersonate'));
        }

        return $next($request);
    }
}
