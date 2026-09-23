<?php

namespace App;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class TrainingTicket extends Model {
    protected $table = 'training_tickets';
    protected static $progress_types = [10=>'No Show', 12=>'Complete', 13=>'Incomplete'];
    protected static ?array $position_types = null;
    public static ?array $session_ids = null;
    public static ?array $position_types_by_rating = null;
    public static ?array $session_ids_by_category = null;
    public static ?array $scheddy_session_id_map = null;

    public static $VATUSA_UPLOAD_STATUS = [
        "PENDING" => 0,
        "UPLOADED" => 1
    ];

    protected static function booted(): void {
        static::saving(function (TrainingTicket $ticket) {
            if (!is_null($ticket->id)) {
                $saved_ticket = TrainingTicket::find($ticket->id);
                if (!is_null($saved_ticket)) {
                    // Prevents a finalized ticket from being marked as a draft... this is a one-way switch
                    $ticket->draft = (!$saved_ticket->draft) ? false : $ticket->draft;
                }
            }
        });
    }

    public static function init(): void {
        self::$position_types = TrainingSortCategory::pluck('category_name', 'id')->all();
        self::$session_ids = TrainingLesson::where('active', true)->pluck('lesson_id', 'id')->toArray();
        self::$position_types_by_rating['S1'] = TrainingSortCategory::where('associated_rating', 2)->pluck('id')->toArray();
        self::$position_types_by_rating['S2'] = TrainingSortCategory::where('associated_rating', 3)->pluck('id')->toArray();
        self::$position_types_by_rating['S3'] = TrainingSortCategory::where('associated_rating', 4)->pluck('id')->toArray();
        self::$position_types_by_rating['C1'] = TrainingSortCategory::where('associated_rating', 5)->pluck('id')->toArray();
        self::$position_types_by_rating['OTHER'] = TrainingSortCategory::whereNull('associated_rating')->pluck('id')->toArray();
        $courses = TrainingCourse::all();
        foreach ($courses as $course) {
            self::$session_ids_by_category[$course->course_id] = TrainingLesson::where('course_id', $course->course_id)->pluck('id')->toArray();
        }
        self::$scheddy_session_id_map = TrainingSortCategory::whereNotNull('scheddy_booking_map')->pluck('id', 'scheddy_booking_map')->toArray();
        self::$scheddy_session_id_map["DEFAULT"] = 124;

    }

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

TrainingTicket::init();
