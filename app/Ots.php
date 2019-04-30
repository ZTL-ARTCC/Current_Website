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
