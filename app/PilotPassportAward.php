<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PilotPassportAward extends Model {
    protected $table = "pilot_passport_award";

    public function getChallengeTitleAttribute() {
        $c = PilotPassport::find($this->challenge_id);
        return $c->title;
    }

    public function getAwardedOnDateAttribute() {
        $d = Carbon::parse($this->awarded_on);
        return $d->format('n/j/Y');
    }
}
