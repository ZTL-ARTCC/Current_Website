<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TrainingRequest extends Model {

	public static $PosReq = [
		1 => 'Minor Delivery',
		2 => 'Major Delivery', 
		3 => 'Minor Ground', 
		4 => 'Major Ground', 
		5 => 'Minor Tower', 
		6 => 'Major Tower', 
		7 => 'Minor Approach',
		8 => 'Major Approach', 
		9 => 'Enroute',
	];

	public static $TimeStart = [
		'00:00' => '00:00', '00:15' => '00:15', '00:30' => '00:30', '00:45' => '00:45',
		'01:00' => '01:00', '01:15' => '01:15', '01:30' => '01:30', '01:45' => '01:45',
		'02:00' => '02:00', '02:15' => '02:15', '02:30' => '02:30', '02:45' => '02:45',
		'03:00' => '03:00', '03:15' => '03:15', '03:30' => '03:30', '03:45' => '03:45',
		'04:00' => '04:00', '04:15' => '04:15', '04:30' => '04:30', '04:45' => '04:45',
		'05:00' => '05:00', '05:15' => '05:15', '05:30' => '05:30', '05:45' => '05:45',
		'06:00' => '06:00', '06:15' => '06:15', '06:30' => '06:30', '06:45' => '06:45',
		'07:00' => '07:00', '07:15' => '07:15', '07:30' => '07:30', '07:45' => '07:45',
		'08:00' => '08:00', '08:15' => '08:15', '08:30' => '08:30', '08:45' => '08:45',
		'09:00' => '09:00', '09:15' => '09:15', '09:30' => '09:30', '09:45' => '09:45',
		'10:00' => '10:00', '10:15' => '10:15', '10:30' => '10:30', '10:45' => '10:45',
		'11:00' => '11:00', '11:15' => '11:15', '11:30' => '11:30', '11:45' => '11:45',
		'12:00' => '12:00', '12:15' => '12:15', '12:30' => '12:30', '12:45' => '12:45',
		'13:00' => '13:00', '13:15' => '13:15', '13:30' => '13:30', '13:45' => '13:45',
		'14:00' => '14:00', '14:15' => '14:15', '14:30' => '14:30', '14:45' => '14:45',
		'15:00' => '15:00', '15:15' => '15:15', '15:30' => '15:30', '15:45' => '15:45',
		'16:00' => '16:00', '16:15' => '16:15', '16:30' => '16:30', '16:45' => '16:45',
		'17:00' => '17:00', '17:15' => '17:15', '17:30' => '17:30', '17:45' => '17:45',
		'18:00' => '18:00', '18:15' => '18:15', '18:30' => '18:30', '18:45' => '18:45',
		'19:00' => '19:00', '19:15' => '19:15', '19:30' => '19:30', '19:45' => '19:45',
		'20:00' => '20:00', '20:15' => '20:15', '20:30' => '20:30', '20:45' => '20:45',
		'21:00' => '21:00', '21:15' => '21:15', '21:30' => '21:30', '21:45' => '21:45',
		'22:00' => '22:00', '22:15' => '22:15', '22:30' => '22:30', '22:45' => '22:45',
		'23:00' => '23:00', '23:15' => '23:15', '23:30' => '23:30', '23:45' => '23:45',
	];

	public static $TimeEnd = [
		'00:00' => '00:00', '00:15' => '00:15', '00:30' => '00:30', '00:45' => '00:45',
		'01:00' => '01:00', '01:15' => '01:15', '01:30' => '01:30', '01:45' => '01:45',
		'02:00' => '02:00', '02:15' => '02:15', '02:30' => '02:30', '02:45' => '02:45',
		'03:00' => '03:00', '03:15' => '03:15', '03:30' => '03:30', '03:45' => '03:45',
		'04:00' => '04:00', '04:15' => '04:15', '04:30' => '04:30', '04:45' => '04:45',
		'05:00' => '05:00', '05:15' => '05:15', '05:30' => '05:30', '05:45' => '05:45',
		'06:00' => '06:00', '06:15' => '06:15', '06:30' => '06:30', '06:45' => '06:45',
		'07:00' => '07:00', '07:15' => '07:15', '07:30' => '07:30', '07:45' => '07:45',
		'08:00' => '08:00', '08:15' => '08:15', '08:30' => '08:30', '08:45' => '08:45',
		'09:00' => '09:00', '09:15' => '09:15', '09:30' => '09:30', '09:45' => '09:45',
		'10:00' => '10:00', '10:15' => '10:15', '10:30' => '10:30', '10:45' => '10:45',
		'11:00' => '11:00', '11:15' => '11:15', '11:30' => '11:30', '11:45' => '11:45',
		'12:00' => '12:00', '12:15' => '12:15', '12:30' => '12:30', '12:45' => '12:45',
		'13:00' => '13:00', '13:15' => '13:15', '13:30' => '13:30', '13:45' => '13:45',
		'14:00' => '14:00', '14:15' => '14:15', '14:30' => '14:30', '14:45' => '14:45',
		'15:00' => '15:00', '15:15' => '15:15', '15:30' => '15:30', '15:45' => '15:45',
		'16:00' => '16:00', '16:15' => '16:15', '16:30' => '16:30', '16:45' => '16:45',
		'17:00' => '17:00', '17:15' => '17:15', '17:30' => '17:30', '17:45' => '17:45',
		'18:00' => '18:00', '18:15' => '18:15', '18:30' => '18:30', '18:45' => '18:45',
		'19:00' => '19:00', '19:15' => '19:15', '19:30' => '19:30', '19:45' => '19:45',
		'20:00' => '20:00', '20:15' => '20:15', '20:30' => '20:30', '20:45' => '20:45',
		'21:00' => '21:00', '21:15' => '21:15', '21:30' => '21:30', '21:45' => '21:45',
		'22:00' => '22:00', '22:15' => '22:15', '22:30' => '22:30', '22:45' => '22:45',
		'23:00' => '23:00', '23:15' => '23:15', '23:30' => '23:30', '23:45' => '23:45',
	];

	protected $table = 'training_controller_requests';

	protected $fillable = array('controller_id', 'position_id', 'date', 'request_begin', 'request_end', 'comments', 
								'accepted', 'mentor', 'session_begin', 'session_end', 'complete');


	public function controller() {
		return $this->hasOne('User', 'id', 'controller_id');
	}

	public function instructor() {
		return $this->hasOne('User', 'id', 'mentor_id');
	}

	public function Trainee() {
		return $this->hasOne('User', 'id', 'trainee_id');
	}

	public function getPosReqAttribute()
	{
		foreach (TrainingRequest::$PosReq as $id => $request) {
			if ($this->position_id == $id) {
				return $request;
			}
		}

		return "";
	}

	public function getTimeStartAttribute()
	{
		foreach (TrainingRequest::$TimeStart as $id => $start) {
			if ($this->request_begin == $id) {
				return $start;
			}
		}

		return "";
	}

	public function getTimeEndAttribute()
	{
		foreach (TrainingRequest::$TimeEnd as $id => $end) {
			if ($this->request_end == $id) {
				return $end;
			}
		}

		return "";
	}

	public static function getForPositionNotCompleted($position)
	{
		return static::query()
			->where('position', $position)
			->where('complete', '0')
			->where(function($query){
				$query->where('date', '>', Carbon::now())
					  ->orWhere('accepted', '1');
			})->get();
	}

	public static function minD()
	{
		return static::getForPositionNotCompleted('1');
	}

	public static function minG()
	{
		return static::getForPositionNotCompleted('2');
	}

	public static function minT()
	{
		return static::getForPositionNotCompleted('3');
	}

	public static function minA()
	{
		return static::getForPositionNotCompleted('4');
	}

	public static function majD()
	{
		return static::getForPositionNotCompleted('5');
	}

	public static function majG()
	{
		return static::getForPositionNotCompleted('6');
	}

	public static function majT()
	{
		return static::getForPositionNotCompleted('7');
	}

	public static function majA()
	{
		return static::getForPositionNotCompleted('8');
	}

	public static function ctr()
	{
		return static::getForPositionNotCompleted('9');
	}
}
