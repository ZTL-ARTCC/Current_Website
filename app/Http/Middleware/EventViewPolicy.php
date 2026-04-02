<?php

namespace App\Http\Middleware;

use App\Event;
use App\EventRegistration;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class EventViewPolicy {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $event_id = null;
        $route = Route::current();

        if ($route->getName() == 'viewEvent') {
            $event_id = $request->route('id');
        } elseif ($route->getName() == 'signupForEvent') {
            $event_id = $request->event_id;
        } elseif ($route->getName() == 'unSignupForEvent') {
            $event_registration = EventRegistration::find($request->route('id'));
            if (!is_null($event_registration)) {
                $event_id = $event_registration->event_id;
            }
        }
        $event = Event::find($event_id);

        if (is_null($event) || ($event->status == 0 && !Auth::user()->hasPermission('events'))) {
            abort(404, 'Not found');
        }
        
        return $next($request);
    }
}
