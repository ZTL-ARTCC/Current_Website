<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PilotPassportEnrollment extends Model {
    protected $table = "pilot_passport_enrollment";

    public function getNameAttribute() {
        $pilot = RealopsPilot::find($this->cid);
        if (!$pilot) {
            return 'Error!';
        }
        return $pilot->fname . ' ' . $pilot->lname;
    }

    public function getEmailAttribute() {
        $pilot = RealopsPilot::find($this->cid);
        if (!$pilot) {
            return 'Error!';
        }
        return $pilot->email;
    }

    public function pilot(): BelongsTo {
        return $this->belongsTo(RealopsPilot::class, 'cid');
    }

    public function getTitleAttribute() {
        $pp = PilotPassport::find($this->challenge_id);
        return $pp->title;
    }

    public function getAirfieldsAttribute() {
        $ret = [];
        $airfields = PilotPassportAirfieldMap::where('mapped_to', $this->challenge_id)->get();
        foreach ($airfields as $mapped_airfield) {
            $a = [
                'airfield_id' => $mapped_airfield->airfield,
                'visited' => null
            ];
            $l = PilotPassportLog::where('cid', $this->cid)->where('airfield', $mapped_airfield->airfield)->first();
            if (!is_null($l)) {
                $a['visited'] = (object) [
                    'visited_on' => $l->visited_on,
                    'callsign' => $l->callsign,
                    'aircraft_type' => $l->aircraft_type
                ];
            }
            $ret[] = (object) $a;
        }
        return $ret;
    }
}
