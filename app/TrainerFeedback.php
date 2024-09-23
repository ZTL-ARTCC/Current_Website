<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainerFeedback extends Model {
    protected $table = 'trainer_feedback';

    public function getServiceLevelTextAttribute() {
        $level = $this->service_level;
        if ($level == 5) {
            return 'Excellent';
        } elseif ($level == 4) {
            return 'Good';
        } elseif ($level == 3) {
            return 'Fair';
        } elseif ($level == 2) {
            return 'Poor';
        } elseif ($level == 1) {
            return 'Unsatisfactory';
        } elseif ($level == 0) {
            return 'N/A';
        } else {
            return 'Value not Found';
        }
    }

    public function getFeedbackIdAttribute() {
        return $this->trainer_id;
    }

    public function setFeedbackIdAttribute($value) {
        $this->trainer_id = $value;
    }

    public function getFeedbackNameAttribute() {
        $controller = User::find($this->feedback_id);
        if (isset($controller)) {
            $name = $controller->full_name;
        } elseif ($this->feedback_id == 0) {
            $name = 'General ATC Feedback';
        } else {
            $name = '[This controller is no longer a member]';
        }
        return $name;
    }
}
