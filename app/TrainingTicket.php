<?php

namespace App;

use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use SimpleXMLElement;

class TrainingTicket extends Model
{
    protected $table = 'training_tickets';
    protected $fillable = ['id', 'controllers_id', 'trainer_id', 'position', 'type', 'date', 'start_time', 'end_time', 'duration', 'comments', 'ins_comments', 'updated_at', 'created_at'];

    public function getTrainerNameAttribute() {
        $user = User::find($this->trainer_id);
        if($user != null) {
            $name = $user->full_name;
        } else {
            $client = new Client();
            $response = $client->request('GET', 'https://cert.vatsim.net/vatsimnet/idstatus.php?cid='.$this->trainer_id);
            $r = new SimpleXMLElement($response->getBody());
            $name = $r->user->name_first.' '.$r->user->name_last;
        }

        return $name;
    }

    public function getControllerNameAttribute() {
        $name = User::find($this->controller_id)->full_name;

        return $name;
    }

    public function getTypeNameAttribute() {
        $pos = $this->type;
        if($pos == 0) {
            $position = 'Classroom Training';
        } elseif($pos == 1) {
            $position = 'Sweatbox Training';
        } elseif($pos == 2) {
            $position = 'Live Training';
        } elseif($pos == 3) {
            $position = 'Live Monitoring';
        } elseif($pos == 4) {
            $position = 'Sweatbox OTS (Pass)';
        } elseif($pos == 5) {
            $position = 'Live OTS (Pass)';
        } elseif($pos == 6) {
            $position = 'Sweatbox OTS (Fail)';
        } elseif($pos == 7) {
            $position = 'Live OTS (Fail)';
        } elseif($pos == 8) {
            $position = 'Live OTS';
        } elseif($pos == 9) {
            $position = 'Sweatbox OTS';
        }

        return $position;
    }

    public function getPositionNameAttribute() {
        $pos = $this->position;
        if($pos == 0) {
            $position = 'Minor Delivery/Ground';
        } elseif($pos == 1) {
            $position = 'Minor Local';
        } elseif($pos == 2) {
            $position = 'Major Delivery/Ground';
        } elseif($pos == 3) {
            $position = 'Major Local';
        } elseif($pos == 4) {
            $position = 'Minor Approach';
        } elseif($pos == 5) {
            $position = 'Major Approach';
        } elseif($pos == 6) {
            $position = 'Center';
        }

        return $position;
    }

    public function getLastTrainingAttribute() {
        $date = $this->date;
        return $date;
    }

    public function getDateEditAttribute() {
        $date = new Carbon($this->date);
        $date = $date->format('Y-m-d');
        return $date;
    }

    public function getDateSortAttribute() {
        $date = strtodate($this->date.' '.$this->time);
        return $date;
    }
}
