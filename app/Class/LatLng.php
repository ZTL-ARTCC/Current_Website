<?php

namespace App\Classes;

class LatLng {
    public int $latitude;
    public int $longitude;

    function __construct($lat, $lon) {
        $this->latitude = $lat;
        $this->longitude = $lon;
    }

    public function toRadian($p): LatLng {
        $p->latitude *= pi() / 180;
        $p->longitude *= -1 * pi() / 180; 
        return $p;
     }
}
