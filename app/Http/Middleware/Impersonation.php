<?php

namespace App\Http\Middleware;

use App\Enums\SessionVariables;
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
        if (session()->has(SessionVariables::IMPERSONATE->value) && Auth::user()->isAbleTo('snrStaff')) {
            Auth::onceUsingId(session(SessionVariables::IMPERSONATE->value));
        }

        return $next($request);
    }
}
