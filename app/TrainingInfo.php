<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingInfo extends Model
{
    protected $table = 'training_info';
    protected $fillable = ['id', 'number', 'section', 'info', 'created_at', 'updated_at'];

    public function getNewNumbersAttribute() {
        $group_number = TrainingInfo::where('section', $this->section)->get()->count() + 1;
        $numbers = range(1, $group_number);

        return $numbers;
    }

    public function getDefaultNewNumberAttribute() {
        $number = TrainingInfo::where('section', $this->section)->get()->count();
        
        return $number;
    }
}
