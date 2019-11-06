<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $table = 'incident_reports';
    protected $fillable = ['id', 'controller_id', 'reporter_id', 'time', 'date', 'description', 'status', 'updated_at', 'created_at'];

    public function getControllerNameAttribute() {
        $user = User::find($this->controller_id);
        if(isset($user)) {
            return $user->full_name;
        } else {
            return '[Hidden: Archived or Controller Removed]';
        }
    }

    public function getReporterNameAttribute() {
        $user = User::find($this->reporter_id);
        if(isset($user)) {
            return $user->full_name;
        } else {
            return '[Hidden: Archived or Controller Removed]';
        }
    }
}
