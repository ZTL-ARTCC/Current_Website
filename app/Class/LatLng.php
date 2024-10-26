<?php

namespace App\Class;

class LatLng {
    public float $latitude;
    public float $longitude;

    public function __construct($lat, $lon) {
        $this->latitude = $lat;
        $this->longitude = $lon;
    }

    public function radLatitude(): float {
        $this->latitude *= pi() / 180;
        return $this->latitude * pi() / 180;
    }

    public function radLongitude(): float {
        return $this->longitude * -1 * pi() / 180;
    }
}
