<?php

namespace App\Class;

class LatLong {
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

    public static function calcDistance($point1, $point2) {
        $dist = acos(sin($point1->radLatitude()) * sin($point2->radLatitude()) + cos($point1->radLatitude())
            * cos($point2->radLatitude()) * cos($point1->radLongitude() - $point2->radLongitude()));
        return (((180 * 60) / pi()) * $dist);
    }
}
