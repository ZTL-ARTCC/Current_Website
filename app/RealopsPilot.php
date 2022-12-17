<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class RealopsPilot extends Authenticatable {
    protected $table = 'realops_pilots';

    public function getFullNameAttribute() {
        return $this->fname . ' ' . $this->lname;
    }

    public function getAssignedFlightAttribute() {
        return RealopsFlight::where('assigned_pilot_id', $this->id)->first();
    }
}
