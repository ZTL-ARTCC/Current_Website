<?php

namespace App;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use SimpleXMLElement;

class Feedback extends Model
{
    protected $table = 'feedback';
    protected $fillable = ['id', 'controller_id', 'position', 'service_level', 'callsign', 'pilot_name', 'pilot_email', 'pilot_cid', 'comments', 'created_at', 'updated_at', 'status'];

    public function getServiceLevelTextAttribute() {
        $level = $this->service_level;
        if($level == 0) {
            return 'Excellent';
        } elseif($level == 1) {
            return 'Good';
        } elseif($level == 2) {
            return 'Fair';
        } elseif($level == 3) {
            return 'Poor';
        } elseif($level == 4) {
            return 'Unsatisfactory';
        } else {
            return 'Value not Found';
        }
    }

    public function getControllerNameAttribute() {
        $controller = User::find($this->controller_id);
        if(isset($controller_id)) {
            $name = $controller->full_name;
        } else {
            $client = new Client();
            $response = $client->request('GET', 'https://cert.vatsim.net/vatsimnet/idstatus.php?cid='.$this->controller_id);
            $r = new SimpleXMLElement($response->getBody());
            $name = $r->user->name_first.' '.$r->user->name_last;
        }
        return $name;
    }
}
