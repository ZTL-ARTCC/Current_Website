<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveEvent extends Model {
    protected $table = 'live_event';

    public function getStaffNameAttribute() {
        $editor = User::find($this->staff_member);
        if (is_null($editor)) {
            return 'Unknown';
        }
        return $editor->full_name;
    }

    public function getUpdateTimeAttribute() {
        $date = $this->updated_at;
        if (is_null($date)) {
            return 'Never';
        }
        return $date->format('D M d, Y').' at '.substr($date, 11).'z';
    }
}
