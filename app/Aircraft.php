<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Aircraft {

    public static $data;
    private static $initialized = false;
    const FILENAME = 'private/aircraft.json';

    public static function init() {
        if (self::$initialized) {
            return 1;
        }
        if (Storage::disk('local')->exists(self::FILENAME)) {
            self::$data = Collection::fromJson(Storage::disk('local')->get(self::FILENAME));
        }
    }

    public static function fetch($acid) {
        $ac = self::$data->where('ac_type', $acid)->first();
        return (is_null($ac)) ? null : (object) $ac;
    }
}
Aircraft::init();
