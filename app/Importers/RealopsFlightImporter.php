<?php

namespace App\Importers;

use App\RealopsFlight;
use Maatwebsite\Excel\Concerns\ToModel;

class RealopsFlightImporter implements ToModel {
    public function model($row) {
        $route = null;

        if (count($row) > 5) {
            $route = $row[5];
        }

        $flight = new RealopsFlight;
        $flight->flight_date = $row[0];
        $flight->flight_number = $row[1];
        $flight->dep_time = $row[2];
        $flight->dep_airport = $row[3];
        $flight->arr_airport = $row[4];
        $flight->route = $route;

        return $flight;
    }
}
