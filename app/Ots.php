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
