<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class Impersonation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('impersonate') && Auth::user()->isAbleTo('snrStaff')) {
            session()->put('impersonating_user', Auth::id());
            Auth::onceUsingId(session('impersonate'));
        }

        return $next($request);
    }
}
