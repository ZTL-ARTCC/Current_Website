<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model {
    protected $table = 'announcement';
    protected $fillable = ['id', 'body', 'staff_member', 'created_at', 'updated_at'];

    public function getStaffNameAttribute(): string {
        $editor = User::find($this->staff_member);
        if (!$editor) {
            return 'Unknown';
        }
        return $editor->full_name;
    }

    public function getUpdateTimeAttribute() {
        $date = $this->updated_at;
        $update = $date->format('D M d, Y').' at '.substr($date, 11).'z';
        return $update;
    }
}
