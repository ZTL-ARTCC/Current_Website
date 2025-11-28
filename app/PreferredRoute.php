<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreferredRoute extends Model {
    protected $table = 'prd_route';
    protected $guarded = [];

    public static function routeLookup(string $departure, string $arrival, string $ac_type = 'jets'): string {
        $routes = PreferredRoute::where('orig', substr($departure, 1))->where('dest', substr($arrival, 1))->where(function ($query) use ($ac_type) {
            $query->where('aircraft', 'LIKE', '%' . strtoupper($ac_type) . '%')->orWhere('aircraft', '');
        })->first();
        if (!$routes) {
            return '';
        }
        /*
            PRDs normally contain the origin and destination ID as the first and last points.
            SimBrief doesn't like this. Remove.
        */
        $route = explode(' ', $routes->route_string);
        if ($route[0] == substr($departure, 1)) {
            unset($route[0]);
        }
        if (end($route) == substr($arrival, 1)) {
            array_pop($route);
        }
        return implode(' ', $route);
    }
}
