<?php

namespace App\Class;

use Illuminate\Support\Facades\Storage;

class PointInPolygon {
    private $polygons = [];
    public $points_in_polygon = [];

    public function __construct(private $points) {
        $this->load_polygons();
        $this->point_in_polygon();
    }

    public function point_in_polygon(): void {
        if (count($this->polygons) == 0) {
            return;
        }

        $pgn = imagecreate(1200, 600);
        // Set green background
        $green = imagecolorallocate($pgn, 0, 255, 0);
        imagefill($pgn, 0, 0, $green);
        // Center drawing on mean of points
        foreach ($this->polygons as $polygon) {
            for ($pt = 0; $pt < count($polygon); $pt++) {
                $northing[] = $polygon[$pt][0];
                $easting[] = $polygon[$pt][1];
            }
        }
        $lat_centroid = (max($northing) + min($northing)) / 2;
        $lon_centroid = (max($easting) + min($easting)) / 2;
        // Scaling
        // Compute diagram scale
        // Find which max value is farthest from airfield centroid (maximum, maximum)
        // Compute upper left coordinates represented by 0,0
        $buffer = 0; //px
        $max_north = max($northing);
        $max_west = min($easting);
        // Compute lower right coordinates represented by maxy,maxx
        $max_south = min($northing);
        $max_east = max($easting);
        $mDist = [];
        $mDist['North'] = imagesy($pgn) / 2 - abs($max_north - $lat_centroid);
        $mDist['South'] = imagesy($pgn) / 2 - abs($max_south - $lat_centroid);
        $mDist['East'] = imagesx($pgn) / 2 - abs($max_east - $lon_centroid);
        $mDist['West'] = imagesx($pgn) / 2 - abs($max_west - $lon_centroid);
        $closestToBorder = array_keys($mDist, min($mDist))[0];
        $factorMax = imagesx($pgn) / 2;
        if (($closestToBorder == 'North') || ($closestToBorder == 'South')) {
            $factorMax = imagesy($pgn) / 2;
        }
        $mScale = ($factorMax - $buffer) / ($factorMax - $mDist[$closestToBorder]);

        // Draw polygons
        $color_num = 1;
        $color_var = 255 / count($this->polygons);
        $shading_key = [];
        foreach ($this->polygons as $polygon_id => $polygon) {
            $color = imagecolorallocate($pgn, floor($color_num * $color_var), 0, 0);
            $shading_key[$polygon_id] = floor($color_num * $color_var);
            $point_array = [];
            foreach ($polygon as $coord_pair) {
                $point_array[] = ($coord_pair[1] - $lon_centroid) * $mScale + (imagesx($pgn) / 2);
                $point_array[] = imagesy($pgn) - (($coord_pair[0] - $lat_centroid) * $mScale + (imagesy($pgn) / 2));
            }
            if (version_compare(phpversion(), '8', '<')) { // Included to support dev environment...
                imagefilledpolygon($pgn, $point_array, count($point_array) / 2, $color); // The 3rd argument is deprecated as of php 8.1
            } else {
                imagefilledpolygon($pgn, $point_array, $color);
            }
            $color_num++;
        }

        // Plot points and get background color
        $points_result = [];
        foreach ($this->points as $point) {
            //Fast check to see if point is within a rectangular bounding box (quickly filters traffic no where close to boundary)
            if ($point[0] > $max_north || $point[0] < $max_south || $point[1] > $max_east || $point[1] < $max_west) {
                $points_result[] = 'false';
                continue;
            }
            $lat = imagesy($pgn) - (($point[0] - $lat_centroid) * $mScale + (imagesy($pgn) / 2));
            $lon = ($point[1] - $lon_centroid) * $mScale + (imagesx($pgn) / 2);
            if ((round($lon, 0) >= 0) && (round($lon, 0) <= imagesx($pgn)) && (round($lat, 0) >= 0) && (round($lat, 0) <= imagesy($pgn))) {
                if (!imagecolorat($pgn, round($lon, 0), round($lat, 0))) {
                    $points_result[] = 'false';
                    continue;
                }
                $rgb = imagecolorsforindex($pgn, imagecolorat($pgn, round($lon, 0), round($lat, 0)));
                if (in_array($rgb['red'], $shading_key)) {
                    $points_result[] = array_search($rgb['red'], $shading_key);
                    continue;
                }
            }
            $points_result[] = 'false';
        }
        $this->points_in_polygon = $points_result;
    }

    private function load_polygons(): void {
        $path = Storage::disk('local')->path('private/boundary.csv');
        if (file_exists($path)) { // Ingest ARTCC boundaries file
            $raw = file_get_contents($path);
            $artcc_boundaries = explode("\n", $raw);
            $multi_artcc_boundary = [];
            foreach ($artcc_boundaries as $ind => $boundary) {
                $tmp = explode(",", $boundary);
                if ($ind > 0 && count($tmp) == 6 && !is_numeric($tmp[0])) {
                    if (!array_key_exists($tmp[0], $multi_artcc_boundary)) {
                        $multi_artcc_boundary[$tmp[0]] = [];
                    }
                    $lat_sign = substr($tmp[3], -1, 1);
                    $lat_adj = ($lat_sign == 'N') ? 0.00001 : -0.00001;
                    $lat = intval(substr($tmp[3], 0, strlen($tmp[3]) - 2)) * $lat_adj;
                    $lon_sign = substr($tmp[4], -1, 1);
                    $lon_adj = ($lon_sign == 'E') ? 0.00001 : -0.00001;
                    $lon = intval(substr($tmp[4], 0, strlen($tmp[4]) - 2)) * $lon_adj;
                    $multi_artcc_boundary[$tmp[0]][] = [$lat, $lon];
                }
            }
            $this->polygons = $multi_artcc_boundary;
        }
    }
}
