<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use SimpleXMLElement;

class Ots extends Model {
    protected $table = 'ots_recommendations';
    protected $fillable = ['controller_id', 'recommender_id', 'position', 'ins_id', 'status', 'updated_at', 'created_at'];

    public function getControllerNameAttribute() {
        $user = User::find($this->controller_id);
        if ($user) {
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
        if ($this->ins_id != null) {
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
        $category = TrainingSortCategory::find($this->position);
        if (!$category) {
            return 'Unknown/Legacy';
        }
        return $category->category_name;
    }

    public function getStatusNameAttribute() {
        return match ($this->status) {
            0 => 'New Recommendation',
            1 => 'Accepted by Instructor',
            2 => 'OTS Complete, Pass',
            3 => 'OTS Complete, Fail',
            default => 'Error!'
        };
    }

    public function getResultAttribute() {
        return match ($this->status) {
            2 => 'Pass',
            3 => 'Fail',
            default => 'Not yet complete.'
        };
    }
}
