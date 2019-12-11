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
            $position = 'S1 T1-DEL-1 (Theory)';
        } elseif($pos == 8) {
            $position = 'S1 P1-DEL-2';
        } elseif($pos == 9) {
            $position = 'S1 P2-DEL-3';
        } elseif($pos == 10) {
            $position = 'S1 M1-DEL-4 (Live Network Monitoring - CLT)';
        } elseif($pos == 11) {
            $position = 'S1T2-DEL-5 (Theroy, ATL)';
        } elseif($pos == 12) {
            $position = 'S1P3-DEL 6';    
        } elseif($pos == 13) {
            $position = 'S1M2-DEL-7 (Live Network Monitoring - ATL)';
        } elseif($pos == 14) {
            $position = 'S1T3-GND-1 (Theory)';
        } elseif($pos == 15) {
            $position = 'S1P4-GND-2';
        } elseif($pos == 16) {
            $position = 'S1P5-GND-3';
        } elseif($pos == 17) {
            $position = 'S1M3-GND-4 (Live Network Monitoring - CLT)';
        } elseif($pos == 18) {
            $position = 'S1T4-GND-5 (Theory, ATL)';
        } elseif($pos == 19) {
            $position = 'S1P6-GND-6';
        } elseif($pos == 20) {
            $position = 'S1P7-GND-7';
        } elseif($pos == 21) {
            $position = 'S1M4-GND-8 (Live Network Monitoring – ATL)';
        } elseif($pos == 22) {
            $position = 'S2T1-TWR-1 (Theory)';                    
        } elseif($pos == 23) {
            $position = 'S2P1-TWR-2';              
        } elseif($pos == 24) {
            $position = 'S2P2-TWR-3'; 
        } elseif($pos == 25) {
            $position = 'S2P3-TWR-4';            
        } elseif($pos == 26) {
            $position = 'S2M1-TWR-5 (Live Network Monitoring – CLT)'; 
        } elseif($pos == 27) {
            $position = 'S2T2-TWR-6 (Theory, ATL)';     
        } elseif($pos == 28) {
            $position = 'S2P4-TWR-7';
        } elseif($pos == 29) {
            $position = 'S2P5-TWR-8';
        } elseif($pos == 30) {
            $position = 'S2M2-TWR-9 (Live Network Monitoring – ATL)';
        } elseif($pos == 31) {
            $position = 'S3T1-APP-1 (Theory)';                        
        } elseif($pos == 32) {
            $position = 'S3T2-APP-2 (Theory)';
        } elseif($pos == 33) {
            $position = 'S3P1-APP-3';          
        } elseif($pos == 34) {
            $position = 'S3P2-APP-4';          
        } elseif($pos == 35) {
            $position = 'S3M1-APP-5 (Live Network Monitoring - BHM/CLT)';
        } elseif($pos == 36) {
            $position = 'S3T3-APP-6 (Theory)'; 
        } elseif($pos == 37) {
            $position = 'S3P3-APP-7'; 
        } elseif($pos == 38) {
            $position = 'S3P3-APP-8';     
        } elseif($pos == 39) {
            $position = 'S3P5-APP-9';
        } elseif($pos == 40) {
            $position = 'S3P6-APP-10';
        } elseif($pos == 41) {
            $position = 'S3M2-APP-11 (Live Network Monitoring – ATL)'; 
        } elseif($pos == 42) {
            $position = 'C1T1-CTR-1 (Theory)';
        } elseif($pos == 43) {
            $position = 'C1P1-CTR-2';    
        } elseif($pos == 44) {
            $position = 'C1P2-CTR-2';
        } elseif($pos == 45) {
            $position = 'C1P3-CTR-3';   
        } elseif($pos == 46) {
            $position = 'C1M2-CTR-4'; 
        } elseif($pos == 46) {
            $position = 'C1M3-CTR-5 (Live Network Monitoring)';
        } elseif($pos == 47) {
            $position = 'C1M4-CTR-6';
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
