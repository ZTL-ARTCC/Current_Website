<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademyExam extends Model {
    protected $table = 'academy_exams';

    public static $EXAM_NAMES = ['BASIC', 'S2', 'S3', 'C1'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'controller_id');
    }
}
