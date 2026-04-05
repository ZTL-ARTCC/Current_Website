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
        $clean_route = self::remove_origin_destination_points($routes->route_string, $departure, $arrival);
        return $clean_route;
    }

    private static function remove_origin_destination_points($route_string, $departure, $arrival): string {
        $route = explode(' ', $route_string);
        if (!is_array($route)) {
            return '';
        }
        if ($route[0] == substr($departure, 1)) {
            unset($route[0]);
        }
        if (end($route) == substr($arrival, 1)) {
            array_pop($route);
        }
        return implode(' ', $route);
    }
}
