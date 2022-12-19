<?php

namespace App\Importers;

use App\RealopsFlight;
use Maatwebsite\Excel\Concerns\ToModel;

class RealopsFlightImporter implements ToModel {
    public function model($row) {
        $est_arr_time = null;
        $route = null;

        if (count($row) > 5) {
            $est_arr_time = $row[5];
        }

        if (count($row) > 6) {
            $route = $row[6];
        }

        $flight = new RealopsFlight;
        $flight->flight_date = $row[0];
        $flight->flight_number = $row[1];
        $flight->dep_time = $row[2];
        $flight->dep_airport = $row[3];
        $flight->arr_airport = $row[4];
        $flight->est_arr_time = $est_arr_time;
        $flight->route = $route;

        return $flight;
    }
}
