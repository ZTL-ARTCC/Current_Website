<?php

namespace App\Http\Middleware;

use App\Event;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EventViewPolicy {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $event_id = null;
        if (!is_null($request->route('id'))) {
            $event_id = $request->route('id');
        } elseif (!is_null($request->event_id)) {
            $event_id = $request->event_id;
        }
        $event = Event::find($event_id);

        if (is_null($event) || ($event->status == 0 && !Auth::user()->hasPermission('events'))) {
            abort(404, 'Not found');
        }
        
        return $next($request);
    }
}
