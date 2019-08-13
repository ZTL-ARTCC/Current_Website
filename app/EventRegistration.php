<?php

namespace App;

use App\EventPosition;
use App\User;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $table = 'event_registration';
    protected $fillable = ['id', 'event_id', 'controller_id', 'position_id', 'start_time', 'end_time', 'status', 'choice_number', 'comments', 'created_at', 'updated_at', 'reminder'];

    public function getControllerNameAttribute() {
        $controller = User::find($this->controller_id);
        if(isset($controller)){
            $name = User::find($this->controller_id)->full_name_rating;
            return $name;
        } else {
            return '[Controller no longer exists]';
        }
    }

    public function getPositionNameAttribute() {
        $name = EventPosition::find($this->position_id)->name;
        return $name;
    }
}
