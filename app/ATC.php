<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ATC extends Model
{
    protected $table = "online_atc";
    protected $fillable = ['id', 'position', 'freq', 'name', 'cid', 'time_logon', 'created_at', 'updated_at'];

    public function getTimeOnlineAttribute() {
        $time_logon = Carbon::createFromTimestamp($this->time_logon);
        $time_now = Carbon::now();

        $time_online = $time_logon->diffInMinutes($time_now) * 60;

        $time = date("H:i", $time_online);

        return $time;
    }

    public function getLogonTimeAttribute() {
        $time = Carbon::createFromTimestamp($this->time_logon);
        return $time->format('m/d/Y H:i').'z';
    }
}
