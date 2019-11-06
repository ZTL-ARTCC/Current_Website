<?php

namespace App;


use App\User;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use SimpleXMLElement;

class Ots extends Model
{
    protected $table = 'ots_recommendations';
    protected $fillable = ['controller_id', 'recommender_id', 'position', 'ins_id', 'status', 'updated_at', 'created_at'];

    public function getControllerNameAttribute() {
        $user = User::find($this->controller_id);
        if($user) {
            $name = $user->full_name;
        } else {
            $client = new Client();
            $response = $client->request('GET', 'https://cert.vatsim.net/vatsimnet/idstatus.php?cid='.$this->controller_id);
            $r = new SimpleXMLElement($response->getBody());
            $name = $r->user->name_first.' '.$r->user->name_last;
        }

        return $name;
    }

    public function getRecommenderNameAttribute() {
        $name = User::find($this->recommender_id)->full_name;

        return $name;
    }

    public function getInsNameAttribute() {
        if($this->ins_id != null) {
            $name = User::find($this->ins_id)->full_name;
        } else {
            $name = 'N/A';
        }

        return $name;
    }

    public function getRecommendedOnAttribute() {
        $date = $this->created_at;
        $result = $date->format('m/d/Y');

        return $result;
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
            $position = 'S1T2-DEL-5 (Theroy)';
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
            $position = 'S1T4-GND-5 (Theory)';
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
            $position = 'S2T2-TWR-6 (Theory)';     
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
        }

        return $position;
    }

    public function getStatusNameAttribute() {
        $status = $this->status;
        if($status == 0) {
            $status_r = 'New Recommendation';
        } elseif($status == 1) {
            $status_r = 'Accepted by Instructor';
        } elseif($status == 2) {
            $status_r = 'OTS Complete, Pass';
        } elseif($status == 3) {
            $status_r = 'OTS Complete, Fail';
        }

        return $status_r;
    }

    public function getResultAttribute() {
        $status = $this->status;
        if($status == 2) {
            $result = 'Pass';
        } elseif($status == 3) {
            $result = 'Fail';
        } else {
            $result = 'Not yet complete.';
        }

        return $result;
    }
}
