<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocalHeroChallenges extends Model {
    protected $table = 'local_hero_challenges';
    private $enroute = ['ZTL'];
    private $approach = ['ATL','CLT','BHM','GSP','AVL','GSO','TYS','CHA','FTY','RYY','AHN'];
    private $local = ['AGS','GMU','GYH','TCL','MXF','MGM','LSF','CSG','MCN','WRB','JQF','VUJ','INT','TRI','LZU','ASN','HKY','PDK'];

    public function getLocalHeroChallengePositionsAttribute() {
        $control_positions = [];
        foreach($this->enroute as $facility) {
            $control_positions[] = $facility . '_CTR';
        }
        foreach($this->approach as $facility) {
            $control_positions[] = $facility . '_APP';
        }
        foreach($this->local as $facility) {
            $control_positions[] = $facility . '_TWR';
            $control_positions[] = $facility . '_GND';
            $control_positions[] = $facility . '_DEL';
        }
        asort($control_positions);
        return $control_positions;
    }
}
