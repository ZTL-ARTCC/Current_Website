<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable {
    use Notifiable;
    use LaratrustUserTrait;
    protected $table = 'roster';
    protected $guarded = [];
    protected $hidden = ['remember_token', 'json_token'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getBackwardsNameAttribute() {
        return $this->lname.', '.$this->fname;
    }

    public function getBackwardsNameRatingAttribute() {
        return $this->backwards_name . ' - ' . $this->rating_short;
    }

    public function getFullNameAttribute() {
        return $this->fname.' '.$this->lname;
    }

    public function getFullNameRatingAttribute() {
        return $this->full_name . ' - ' . $this->rating_short;
    }

    public static $RatingShort = [
        0 => 'N/A',
        1 => 'OBS', 2 => 'S1',
        3 => 'S2', 4 => 'S3',
        5 => 'C1', 7 => 'C3',
        8 => 'I1', 10 => 'I3',
        11 => 'SUP', 12 => 'ADM',
    ];

    private static $letters = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
    ];

    public static function generateControllerInitials(String $fname, String $lname) {
        $first_initial = strtoupper(substr($fname, 0, 1));
        $last_initial = strtoupper(substr($lname, 0, 1));
        $change_first = true;

        while (User::controllerExistsWithInitials($first_initial . $last_initial)) {
            $next_char = User::$letters[random_int(0, 25)];
            if ($change_first) {
                $first_initial = $next_char;
            } else {
                $last_initial = $next_char;
            }
        }

        return $first_initial . $last_initial;
    }

    private static function controllerExistsWithInitials(String $initials) {
        return User::where('initials', $initials)->exists();
    }

    public function getRatingShortAttribute() {
        foreach (User::$RatingShort as $id => $Short) {
            if ($this->rating_id == $id) {
                return $Short;
            }
        }

        return "";
    }

    public function getRatingLongAttribute() {
        foreach (User::$RatingLong as $id => $Short) {
            if ($this->rating_id == $id) {
                return $Short;
            }
        }

        return "";
    }

    public static $RatingLong = [
        0 => 'Pilot',
        1 => 'Observer (OBS)', 2 => 'Student 1 (S1)',
        3 => 'Student 2 (S2)', 4 => 'Senior Student (S3)',
        5 => 'Controller (C1)', 7 => 'Senior Controller (C3)',
        8 => 'Instructor (I1)', 10 => 'Senior Instructor (I3)',
        11 => 'Supervisor (SUP)', 12 => 'Admin (ADM)',
    ];

    public static $StatusText = [
        1 => 'Active',
        0 => 'LOA',
        2 => 'Inactive'
    ];

    public static function getUserStatusAttribute() {
        return array_filter(self::$StatusText);
    }

    protected const FACILITY_STAFF_POSITION_MAP = [
        1 => 'ATM',
        2 => 'DATM',
        3 => 'TA',
        4 => 'ATA',
        5 => 'WM',
        6 => 'AWM',
        7 => 'FE',
        8 => 'AFE',
        9 => 'EC'
    ];

    protected static $FacilityStaff = [0 => 'NONE', ...self::FACILITY_STAFF_POSITION_MAP];

    public static function getFacilityStaffAttribute() {
        return array_filter(self::$FacilityStaff);
    }

    protected static $EventsStaff = [
        0 => 'NONE',
        1 => 'AEC',
        2 => 'AEC (Ghost)',
        3 => 'Events Team'
    ];

    public static function getEventsStaffAttribute() {
        return array_filter(self::$EventsStaff);
    }

    protected static $TrainingStaff = [
        0 => 'NONE',
        1 => 'MTR',
        2 => 'INS'
    ];

    public static function getTrainingStaffAttribute() {
        return array_filter(self::$TrainingStaff);
    }

    protected const TRAIN_UNABLE = 0;
    protected const TRAIN_UNRES_GND = 1;
    protected const TRAIN_CLT_DEL_GND = 2;
    protected const TRAIN_UNRES_TWR = 3;
    protected const TRAIN_CLT_TWR = 4;
    protected const TRAIN_ATL_TWR = 5;
    protected const TRAIN_UNRES_APP = 6;
    protected const TRAIN_CLT_APP = 7;
    protected const TRAIN_ATL_APP = 8;
    protected const TRAIN_CTR = 9;

    protected static $TrainingLevel = [
        self::TRAIN_UNABLE => 'Unable to Train',
        self::TRAIN_UNRES_GND => 'Unrestricted DEL & GND',
        self::TRAIN_CLT_DEL_GND => 'CLT DEL & GND',
        self::TRAIN_UNRES_TWR => 'Unrestricted TWR',
        self::TRAIN_CLT_TWR => 'CLT TWR',
        self::TRAIN_ATL_TWR => 'ATL TWR',
        self::TRAIN_UNRES_APP => 'Unrestricted Approach',
        self::TRAIN_CLT_APP => 'CLT Approach',
        self::TRAIN_ATL_APP => 'ATL (A80) Approach',
        self::TRAIN_CTR => 'Center'
    ];

    public static function getTrainingLevelAttribute() {
        return array_filter(self::$TrainingLevel);
    }

    protected const UNCERTIFIED = 0;
    protected const CERTIFIED = 1;
    protected const SOLO_CERTIFICATION = 99;
    protected const TRACON_SAT_CERTIFIED = 90;
    protected const TRACON_DR_CERTIFIED = 91;
    protected const TRACON_TAR_CERTIFIED = 92;
    protected const LEGACY_MINOR_CERTIFIED = 1;
    protected const LEGACY_MAJOR_CERTIFIED = 2;

    protected const SOLO_CERT_DURATION = 30; // Duration of solo certs in days

    public static function getMagicNumber($const_name) {
        return (defined('self::'.$const_name)) ? constant('self::'.$const_name) : null;
    }

    protected static $UncertifiedCertified = [
        self::UNCERTIFIED => 'None',
        self::CERTIFIED => 'Certified'
    ];

    public static function getUncertifiedCertifiedAttribute() {
        return array_filter(self::$UncertifiedCertified);
    }

    protected static $UncertifiedSoloCertified = [
        self::UNCERTIFIED => 'None',
        self::SOLO_CERTIFICATION => 'Solo Certification',
        self::CERTIFIED => 'Certified'
    ];

    public static function getUncertifiedSoloCertifiedAttribute() {
        return array_filter(self::$UncertifiedSoloCertified);
    }

    protected static $UncertifiedCertifiedA80 = [
        self::UNCERTIFIED => 'None',
        self::TRACON_SAT_CERTIFIED => 'A80 SAT Certified',
        self::TRACON_DR_CERTIFIED => 'A80 DR Certified',
        self::TRACON_TAR_CERTIFIED => 'A80 TAR Certified',
        self::CERTIFIED => 'Certified'
    ];

    public static function getUncertifiedCertifiedA80Attribute() {
        return array_filter(self::$UncertifiedCertifiedA80);
    }

    public function getStatusTextAttribute() {
        foreach (User::$StatusText as $id => $Status) {
            if ($this->status == $id) {
                return $Status;
            }
        }

        return "";
    }

    public function getStaffPositionAttribute() {
        if ($this->hasRole('atm')) {
            return 1;
        } elseif ($this->hasRole('datm')) {
            return 2;
        } elseif ($this->hasRole('ta')) {
            return 3;
        } elseif ($this->hasRole('ata')) {
            return 4;
        } elseif ($this->hasRole('wm')) {
            return 5;
        } elseif ($this->hasRole('awm')) {
            return 6;
        } elseif ($this->hasRole('fe')) {
            return 7;
        } elseif ($this->hasRole('afe')) {
            return 8;
        } elseif ($this->hasRole('ec')) {
            return 9;
        } elseif ($this->hasRole('aec')) {
            return 10;
        } elseif ($this->hasRole('aec-ghost')) {
            return 11;
        } elseif ($this->hasRole('events-team')) {
            return 12;
        } else {
            return 0;
        }
    }

    public function getEventsPositionAttribute() {
        if ($this->hasRole('aec')) {
            return 1;
        } elseif ($this->hasrole('aec-ghost')) {
            return 2;
        } elseif ($this->hasrole('events-team')) {
            return 3;
        } else {
            return 0;
        }
    }

    public function getTrainPositionAttribute() {
        if ($this->hasRole('mtr')) {
            return 1;
        } elseif ($this->hasrole('ins')) {
            return 2;
        } else {
            return 0;
        }
    }

    public function getLastTrainingAttribute() {
        $tickets_sort = TrainingTicket::where('controller_id', $this->id)->get()->sortByDesc(function ($t) {
            return strtotime($t->date.' '.$t->start_time);
        })->pluck('id');
        if ($tickets_sort->count() != 0) {
            $tickets_order = implode(',', array_fill(0, count($tickets_sort), '?'));
            $last_training = TrainingTicket::whereIn('id', $tickets_sort)->orderByRaw("field(id,{$tickets_order})", $tickets_sort)->first();
        } else {
            $last_training = null;
        }

        if ($last_training != null) {
            return $last_training->date;
        } else {
            return null;
        }
    }

    public function getLastTrainingGivenAttribute() {
        $tickets_sort = TrainingTicket::where('trainer_id', $this->id)->get()->sortByDesc(function ($t) {
            return strtotime($t->date.' '.$t->start_time);
        })->pluck('id');
        if ($tickets_sort->count() != 0) {
            $tickets_order = implode(',', array_fill(0, count($tickets_sort), '?'));
            $last_training_given = TrainingTicket::whereIn('id', $tickets_sort)->orderByRaw("field(id,{$tickets_order})", $tickets_sort)->first();
        } else {
            $last_training_given = null;
        }

        if ($last_training_given != null) {
            return $last_training_given->date;
        } else {
            return null;
        }
    }

    public function getLastLogonAttribute() {
        $last = ControllerLog::where('cid', $this->id)->orderBy('created_at', 'DESC')->first();
        if ($last != null) {
            $date = Carbon::parse($last->created_at)->format('m/d/Y');
        } else {
            $date = 'Never';
        }

        return $date;
    }

    public function getTextDateJoinAttribute() {
        $date = Carbon::parse($this->added_to_facility)->format('m/d/Y');

        return $date;
    }

    public function getTextDateCreateAttribute() {
        $date = Carbon::parse($this->created_at)->format('m/d/Y');

        return $date;
    }

    public function getSoloAttribute() {
        $cert = SoloCert::where('cid', $this->id)->where('status', 0)->first();
        if ($cert) {
            $date = Carbon::parse($cert->expiration)->format('m/d/Y');
        } else {
            $date = 'N/A';
        }

        return $date;
    }
}
