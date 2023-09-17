<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocalHeroChallenges extends Model {
    protected $table = 'local_hero_challenges';
    private static $enroute = ['ZTL'];
    private static $approach = ['ATL','CLT','BHM','GSP','AVL','GSO','TYS','CHA','FTY','RYY','AHN'];
    private static $local = ['AGS','GMU','GYH','TCL','MXF','MGM','LSF','CSG','MCN','WRB','JQF','VUJ','INT','TRI','LZU','ASN','HKY','PDK'];

    public static function getLocalHeroChallengePositions() {
        $control_positions = [];
        foreach (static::$enroute as $facility) {
            $control_positions[] = $facility . '_CTR';
        }
        foreach (static::$approach as $facility) {
            $control_positions[] = $facility . '_APP';
        }
        foreach (static::$local as $facility) {
            $control_positions[] = $facility . '_TWR';
            $control_positions[] = $facility . '_GND';
            $control_positions[] = $facility . '_DEL';
        }
        asort($control_positions);
        return $control_positions;
    }
}
