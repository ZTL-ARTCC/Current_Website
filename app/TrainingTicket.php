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
        }elseif($pos == 10){
            $position = 'No Show';
        }elseif($pos == 11){
            $position = 'No Show';
        }elseif($pos == 12){
            $position = 'Complete';
        }elseif($pos == 13){
            $position = 'Incomplete';
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
        } elseif($pos == 7) {
            $position = 'Class D/C Clearance Delivery';
        } elseif($pos == 8) {
            $position = 'Class D/C Clearance Delivery';
        } elseif($pos == 9) {
            $position = 'Class D/C Clearance Delivery';
        } elseif($pos == 10) {
            $position = 'Class B Clearance Delivery';
        } elseif($pos == 11) {
            $position = 'ATL Clearance Delivery Theory';
        } elseif($pos == 12) {
            $position = 'ATL Clearance Delivery';
        } elseif($pos == 13) {
            $position = 'ATL Clearance Delivery';
        } elseif($pos == 14) {
            $position = 'Class D/C Ground';
        } elseif($pos == 15) {
            $position = 'Class D/C Ground';
        } elseif($pos == 16) {
            $position = 'Class D/C Ground';
        } elseif($pos == 17) {
            $position = 'Class B Ground';
        } elseif($pos == 18) {
            $position = 'ATL Ground Theory';
        } elseif($pos == 19) {
            $position = 'ATL Ground';
        } elseif($pos == 20) {
            $position = 'ATL Ground';
        } elseif($pos == 21) {
            $position = 'ATL Ground';
        } elseif($pos == 22) {
            $position = 'Class D Tower';
        } elseif($pos == 23) {
            $position = 'Class D Tower';
        } elseif($pos == 24) {
            $position = 'Class C Tower';
        } elseif($pos == 25) {
            $position = 'Class B Tower';
        } elseif($pos == 26) {
            $position = 'Class B Tower';
        } elseif($pos == 27) {
            $position = 'ATL Tower Theory';
        } elseif($pos == 28) {
            $position = 'ATL Tower';
        } elseif($pos == 29) {
            $position = 'ATL Tower';
        } elseif($pos == 30) {
            $position = 'ATL Tower';
        } elseif($pos == 31) {
            $position = 'Minor Approach Introduction';
        } elseif($pos == 32) {
            $position = 'Minor Approach Introduction';
        } elseif($pos == 33) {
            $position = 'Minor Approach';
        } elseif($pos == 34) {
            $position = 'CLT Approach';
        } elseif($pos == 35) {
            $position = 'CLT Approach';
        } elseif($pos == 36) {
            $position = 'A80 Departure/Satellite Radar';
        } elseif($pos == 37) {
            $position = 'A80 Departure/Satellite Radar';
        } elseif($pos == 38) {
            $position = 'A80 Terminal Arrival Radar';
        } elseif($pos == 39) {
            $position = 'A80 Arrival Radar';
        } elseif($pos == 40) {
            $position = 'A80 Arrival Radar';
        } elseif($pos == 41) {
            $position = 'A80 Arrival Radar';
        } elseif($pos == 42) {
            $position = 'Atlanta Center Introduction';
        } elseif($pos == 43) {
            $position = 'Atlanta Center';
        } elseif($pos == 44) {
            $position = 'Atlanta Center';
        } elseif($pos == 45) {
            $position = 'Atlanta Center';
        } elseif($pos == 46) {
            $position = 'Atlanta Center';
        } elseif($pos == 46) {
            $position = 'Atlanta Center';
        } elseif($pos == 47) {
            $position = 'Atlanta Center';
        }  elseif($pos == 48) {
            $position = 'S1 Visiting Major Checkout';
        }  elseif($pos == 49) {
            $position = 'S2 Visiting Major Checkout';
        }  elseif($pos == 50) {
            $position = 'S3 Visiting Major Checkout';
        } elseif($pos == 51) {
            $position = 'C1 Visiting Major Checkout';
        } elseif($pos == 52) {
            $position = 'Enroute OTS';
        } elseif($pos == 53) {
            $position = 'Approach OTS';
        } elseif($pos == 54) {
            $position = 'Local OTS';
        } elseif($pos == 100) {
            $position = 'ZTL On-Boarding';
        } elseif($pos == 101) {
            $position = 'Class D/C Clearance Delivery';
        } elseif($pos == 102) {
            $position = 'Class B Clearance Delivery';
        } elseif($pos == 103) {
            $position = 'ATL Clearance Delivery Theory';
        } elseif($pos == 104) {
            $position = 'ATL Clearance';
        } elseif($pos == 105) {
            $position = 'Class D/C Ground';
        } elseif($pos == 106) {
            $position = 'Class B Ground';
        } elseif($pos == 107) {
            $position = 'ATL Ground Theory';
        } elseif($pos == 108) {
            $position = 'ATL Ground';
        } elseif($pos == 109) {
            $position = 'Class D Tower';
        } elseif($pos == 110) {
            $position = 'Class C Tower';
        } elseif($pos == 111) {
            $position = 'Class B Tower';
        } elseif($pos == 112) {
            $position = 'ATL Tower Theory';
        } elseif($pos == 113) {
            $position = 'ATL Tower';
        } elseif($pos == 114) {
            $position = 'Minor Approach Introduction';
        } elseif($pos == 115) {
            $position = 'Minor Approach';
        } elseif($pos == 116) {
            $position = 'CLT Approach';
        } elseif($pos == 117) {
            $position = 'A80 Departure/Satellite Radar';
        } elseif($pos == 118) {
            $position = 'A80 Terminal Arrival Radar';
        } elseif($pos == 119) {
            $position = 'A80 Arrival Radar';
        } elseif($pos == 120) {
            $position = 'Atlanta Center Introduction';
        } elseif($pos == 121) {
            $position = 'Atlanta Center';
        } elseif($pos == 122) {
            $position = 'Recurrent Training';
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
