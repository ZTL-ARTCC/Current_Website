<?php

namespace App;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcement';
    protected $fillable = ['id', 'body', 'staff_member', 'created_at', 'updated_at'];

    public function getStaffNameAttribute() {
        $name = User::find($this->staff_member)->full_name;

        return $name;
    }

    public function getUpdateTimeAttribute() {
        $date = $this->updated_at;
        $update = $date->format('D M d, Y').' at '.substr($date, 11).'z';
        return $update;
    }
}
