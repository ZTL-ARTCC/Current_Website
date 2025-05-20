<?php

namespace App;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class TrainingTicket extends Model {
    protected $table = 'training_tickets';
    // ***Null values in the arrays below denote legacy IDs that we don't want to repurpose. Will display as 'Legacy' in select menus.
    protected static $progress_types = [10=>'No Show', 12=>'Complete', 13=>'Incomplete'];
    protected static $position_types = [100=>'ZTL On-Boarding', 101=>'Unrestricted Clearance', 105=>'Unrestricted Ground', 109=>'Unrestricted Tower',
                                 115=>'Unrestricted Approach', 123=>null, 102=>'T1 CLT Delivery', 106=>'T1 CLT Ground', 111=>'T1 CLT Tower',
                                 116=>'T1 CLT Approach', 104=>'T1 ATL Clearance', 108=>'T1 ATL Ground', 113=>'T1 ATL Tower',
                                 117 => 'A80 Departure/Satellite Radar', 118 => 'A80 Terminal Arrival Radar', 119 => 'A80 Arrival Radar',
                                 121 => 'Atlanta Center', 122 => 'Recurrent Training', 124 => 'Other', 125 => 'Mentor Training'];
    public static $session_ids = [  200=>'DEL1', 201=>'DEL2', 202=>'DEL3', 205=>'GND1', 203=>'CC1', 204=>null, 258=>'CC2', 206=>'TWR1',
                                207=>'TWR2', 208=>'TWR3', 209=>'TWR4', 210=>'TWR5', 211=>'TWR6', 261=>'TWR7', 212=>'CC3', 262=>'CC4',
                                213=>'CC5', 214=>'CC6', 215=>null, 216=>'APP1', 217=>'APP2', 218=>'APP3', 219=>'APP4', 220=>'APP5',
                                221=>'APP6', 222=>'APP7', 225=>'CT1', 226=>'CT2', 223=>'CT3', 224=>null, 263=>'CT4', 227=>'CT5', 228=>null,
                                229=>'CTR1', 260=>'CTR2', 230=>'CTR3', 232=>'CTR4', 233=>'CTR5', 231=>'CTR6', 234=>null, 235=>'CTR7',
                                236=>'ZTL1', 237=>'ATL1', 238=>null, 239=>'ATL2', 242=>'ATL3', 243=>'ATL4', 240=>'ATL5', 259=>'ATL6',
                                241=>'ATL7', 244=>'ATL8', 245=>'A801', 246=>'A802', 247=>null, 248=>'A803', 249=>null, 250=>'A804',
                                251=>null, 252=>'A805',253=>'A806',254=>'A807', 255=>'A808', 256=>null, 257=>'Other'];
    public static $position_types_by_rating = [
        "S1" => [100, 101, 105, 102, 106],
        "S2" => [109, 111, 104, 108, 113],
        "S3" => [115, 116, 117, 118, 119],
        "C1" => [121],
        "OTHER" => [122, 124, 125]
    ];
    public static $session_ids_by_category = [
        "S1" => [200, 201, 202, 205],
        "S2" => [206, 207, 208, 209, 210, 211, 261],
        "S3" => [216, 217, 218, 219, 220, 221, 222],
        "C1" => [229, 260, 230, 232, 233, 231, 235],
        "CLT_ATCT" => [203, 258, 212, 262, 213, 214],
        "CLT_APP" => [225, 226, 223, 263, 227],
        "ATL_ATCT" => [237, 239, 242, 243, 240, 259, 241, 244],
        "A80" => [245, 246, 248, 250, 252,253, 254, 255]
    ];

    public static $VATUSA_UPLOAD_STATUS = [
        "PENDING" => 0,
        "UPLOADED" => 1
    ];

    public function getTrainerNameAttribute() {
        $user = User::find($this->trainer_id);
        if ($user != null) {
            $name = $user->full_name;
        } else {
            $client = new Client();
            $response = $client->get('https://api.vatsim.net/api/ratings/'.$r->cid.'/', ['headers' => ['Content-Type' => 'application/x-www-form-urlencoded','Authorization' => 'Token ' . Config::get('vatsim.api_key', '')]]);
            $res = json_decode($response->getBody(), true);
            $name = '';
            if (array_key_exists('name_first', $res)&&array_key_exists('name_last', $res)) {
                $name = $res['name_first'] . ' ' . $res['name_last'];
            }
        }

        return $name;
    }

    public function getControllerNameAttribute() {
        $name = User::find($this->controller_id)->full_name;
        return $name;
    }

    public function getTypeNameAttribute() { // Lookup for session types (now progress type)
        if (key_exists($this->type, self::$progress_types)) {
            return self::$progress_types[$this->type];
        }

        if ($this->draft) {
            return null;
        }

        return 'Legacy';
    }

    public static function getProgressSelectAttribute() { // Returns array of progress types for the new/edit ticket views
        return array_filter(self::$progress_types);
    }

    public function getPositionNameAttribute() { // Lookup for session categories
        if (key_exists($this->position, self::$position_types)) {
            return self::$position_types[$this->position];
        }

        if ($this->draft) {
            return null;
        }

        return 'Legacy';
    }

    public static function getPositionSelectAttribute() { // Returns array of sessions for the new ticket view
        return array_filter(self::$position_types);
    }

    public function getSessionNameAttribute() { // Lookup for training session name ex: 'ATL1'
        if (key_exists($this->session_id, self::$session_ids)) {
            return self::$session_ids[$this->session_id];
        }

        if ($this->draft) {
            return null;
        }

        return 'Legacy';
    }

    public static function getSessionSelectAttribute() { // Returns array of sessions for the new ticket view
        return array_filter(self::$session_ids);
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

    public function getIsVatusaSyncedAttribute() {
        return $this->vatusa_upload_status == $this::$VATUSA_UPLOAD_STATUS["UPLOADED"];
    }
}
