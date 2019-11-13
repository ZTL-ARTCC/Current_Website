<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TrainingRequest;
use Mail;

class MentorAvail extends Model {

	protected $table = 'mentor_training_times';

	protected $fillable = array('mentor_id', 'slot', 'trainee_id', 'position_id', 'trainee_comments');

	public function mentor() {
		return $this->hasOne('app\User', 'id', 'mentor_id');
	}

	public function Trainee() {
		return $this->hasOne('app\User', 'id', 'trainee_id');
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

	public function sendNewSessionEmail() {
		return Mail::send('emails.training.new_session', ['session' => $this], function($message){
			$message->from('training@notams.ztlartcc.org', 'vZTL Training Depatment');
			$message->to($this->mentor->email)->cc($this->trainee->email);
			$message->subject('ZTL - New Session');
		});
	}

	public function sendCancellationEmail() {
		return Mail::send('emails.training.cancel_session', ['session' => $this], function($message){
			$message->from('training@notams.ztlartcc.org', 'vZTL Training Depatment');
			$message->to($this->mentor->email)->cc($this->trainee->email);
			$message->subject('ZTL - Session Canceled');
		});
	}
}
