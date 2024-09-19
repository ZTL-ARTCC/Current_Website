<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveEvent extends Model {
    protected $table = 'live_event';

    private function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }

    public static function getAnnouncement(): LiveEvent {
        $announcement = LiveEvent::first();
        if (!$announcement) {
            $announcement = new LiveEvent;
        }
        return $announcement;
    }

    public function getStaffNameAttribute(): string {
        $editor = User::find($this->staff_member);
        if (!$editor) {
            return 'Unknown';
        }
        return $editor->full_name;
    }

    public function getUpdateTimeAttribute(): string {
        $date = $this->updated_at;
        if (!$date) {
            return 'Never';
        }
        return $date->format('D M d, Y').' at '.substr($date, 11).'z';
    }
}
