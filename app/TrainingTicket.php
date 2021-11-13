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
    protected $fillable = ['id', 'controllers_id', 'trainer_id', 'position', 'session_id', 'type', 'date', 'start_time', 'end_time', 'duration', 'comments', 'ins_comments', 'updated_at', 'created_at'];

    public function getTrainerNameAttribute() {
        $user = User::find($this->trainer_id);
        if($user != null) {
            $name = $user->full_name;
        } else {
            $client = new Client();
            //$response = $client->request('GET', 'https://cert.vatsim.net/vatsimnet/idstatus.php?cid='.$this->trainer_id);
            //$r = new SimpleXMLElement($response->getBody());
            //$name = $r->user->name_first.' '.$r->user->name_last;
			$response = $client->get('https://api.vatsim.net/api/ratings/'.$r->cid.'/', ['headers' => ['Content-Type' => 'application/x-www-form-urlencoded','Authorization' => 'Token ' . Config::get('vatsim.api_key','')]]);
            $res = json_decode($response->getBody(),true);
            $name = '';
            if(array_key_exists('name_first',$res)&&array_key_exists('name_last',$res)) {
                            $name = $res['name_first'] . ' ' . $res['name_last'];
            }
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
		} elseif($pos == 123) {
            $position = 'BHM Approach';
        }

        return $position;
    }
	
    public function getSessionNameAttribute() {
		$session = '';
        $pos = $this->session_id;
        if($pos == 200) {
            $session = 'DEL0 - S1T0-O';
        } elseif($pos == 201) {
            $session = 'DEL1 - S1T1';
        } elseif($pos == 202) {
            $session = 'DEL2 - S1P1';
        } elseif($pos == 203) {
            $session = 'DEL3 - S1T2';
        } elseif($pos == 204) {
            $session = 'DEL4 - S1P2-S';
        } elseif($pos == 205) {
            $session = 'GND1 - S1T3-S';
        } elseif($pos == 206) {
            $session = 'TWR1 - S2T1';
        } elseif($pos == 207) {
            $session = 'TWR2 - S2T2';
        } elseif($pos == 208) {
            $session = 'TWR3 - S2P1-S';
        } elseif($pos == 209) {
            $session = 'TWR4 - S2T3-S';
        }elseif($pos == 210){
            $session = 'TWR5 - S2P2-S';
        }elseif($pos == 211){
            $session = 'TWR6 - S2T4-S';
        }elseif($pos == 212){
            $session = 'TWR7 - S2T5-S';
        }elseif($pos == 213){
            $session = 'TWR8 - S2T6';
        }elseif($pos == 214){
            $session = 'TWR9 - S2P3-O';
		}elseif($pos == 215){
            $session = 'TWR10 - S2M1-S';
		}elseif($pos == 216){
            $session = 'APP1 - S3T1-S';
		}elseif($pos == 217){
            $session = 'APP2 - S3T2-S';
		}elseif($pos == 218){
            $session = 'APP3 - S3P1-S';
		}elseif($pos == 219){
            $session = 'APP4 - S3T3-S';
		}elseif($pos == 220){
            $session = 'APP5 - S3T4-S';
		}elseif($pos == 221){
            $session = 'APP6 - S3T5-S';
		}elseif($pos == 222){
            $session = 'APP7 - S3M1-S';
		}elseif($pos == 223){
            $session = 'APP8 - S3T6';
		}elseif($pos == 224){
            $session = 'APP9 - S3P2';
		}elseif($pos == 225){
            $session = 'APP10 - S3P3-O';
		}elseif($pos == 226){
            $session = 'APP11 - S3P4-O';
		}elseif($pos == 227){
            $session = 'APP12 - S3M2-S';
		}elseif($pos == 228){
            $session = 'CTR0 - C1T0-O';
		}elseif($pos == 229){
            $session = 'CTR1 - C1T1-S';
		}elseif($pos == 230){
            $session = 'CTR2 - C1T2-S';
		}elseif($pos == 231){
            $session = 'CTR3 - C1T3-S';
		}elseif($pos == 232){
            $session = 'CTR4 - C1P1-S';
		}elseif($pos == 233){
            $session = 'CTR5 - C1P2-S';
		}elseif($pos == 234){
            $session = 'CTR6 - C1M1-S';
		}elseif($pos == 235){
            $session = 'CTR7 - C1M2-S';
		}elseif($pos == 236){
            $session = 'ZTL1 - M1M1-S';
		}elseif($pos == 237){
            $session = 'ATL1 - M2T1';
		}elseif($pos == 238){
            $session = 'ATL2 - M2M1-O';
		}elseif($pos == 239){
            $session = 'ATL3 - M2T2';
		}elseif($pos == 240){
            $session = 'ATL4 - M2P1';
		}elseif($pos == 241){
            $session = 'ATL5 - M2M2-O';
		}elseif($pos == 242){
            $session = 'ATL6 - M2T3';
		}elseif($pos == 243){
            $session = 'ATL7 - M2T4';
		}elseif($pos == 244){
            $session = 'ATL8 - M2M3-S';
		}elseif($pos == 245){
            $session = 'A801 - M3P1-S';
		}elseif($pos == 246){
            $session = 'A802 - M3M1-O';
		}elseif($pos == 247){
            $session = 'A803 - M3P2';
		}elseif($pos == 248){
            $session = 'A804 - M3M2-O';
		}elseif($pos == 249){
            $session = 'A805 - M3T1-S';
		}elseif($pos == 250){
            $session = 'A806 - M3P3';
		}elseif($pos == 251){
            $session = 'A807 - M3P4';
		}elseif($pos == 252){
            $session = 'A808 - M3M3-O';
		}elseif($pos == 253){
            $session = 'A809 - M3T3-O';
		}elseif($pos == 254){
            $session = 'A8010 - M3T4-S';
		}elseif($pos == 255){
            $session = 'A8011 - M3P5';
		}elseif($pos == 256){
            $session = 'A8012 - M3M4-S';
		}elseif($pos == 257){
            $session = 'Unlisted/other';
        }
        return $session;

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
