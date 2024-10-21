<?php

namespace App;

use App\Classes\LatLng;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PilotPassportAirfield extends Model {
    protected $table = "pilot_passport_airfield";

    public function fetchLatLng(): LatLng {
        return new LatLng($this->latitude, $this->longitude);
    }

    public static function getAirfieldsByChallenge($id) {
        $airfields = SELF::mapping()->where('mapped_to', $id)->get();
        dd($airfields);
        //return PilotPassportAirfield::select('id')->mappings()->where('mapped_to', $id);
        /*
        //return 'test';
        $mapping = PilotPassportAirfieldMap::where('mapped_to', $id)->get();
        //return $mapping;
        $a = null;
        foreach ($mapping as $m) {
            $res = PilotPassportAirfield::where('id', $m->airfield)->get();
            if (is_null($a)) {
                $a = $res;
            }
            else {
                $a = $a->merge($res);
            }
        }
        return $a;
        */
    }
    public function mapping(): BelongsTo
    {
        return $this->belongsTo(PilotPassportAirfieldMap::class, 'airfield', 'id');
    }

}
