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
        } elseif($level == 5) {
            return 'N/A';
        } else {
            return 'Value not Found';
        }
    }

    public function getControllerNameAttribute() {
        $controller = User::find($this->controller_id);
        if(isset($controller)) {
            $name = $controller->full_name;
        } else {
            $name = '[This controller is no longer a member]';
        }
        return $name;
    }
}
