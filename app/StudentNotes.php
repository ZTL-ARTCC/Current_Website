<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StudentNotes extends Model {
    public $table = 'student_notes';
    public $incrementing = false;
    protected $fillable = ['id', 'note', 'entered_by'];
    protected $guarded = [];

    public function getStudentNameAttribute(): string {
        $student = User::find($this->id);
        if (!$student) {
            return 'Unknown';
        }
        return $student->full_name;
    }

    public function getEnteredByNameAttribute(): string {
        $editor = User::find($this->entered_by);
        if (!$editor) {
            return 'Unknown';
        }
        return $editor->full_name;
    }

    public function getLastModifiedAttribute(): string {
        $timestamp = $this->updated_at ?: $this->created_at;
        if (!$timestamp) {
            return 'Unknown';
        }
        return Carbon::parse($timestamp)->format('m/d/Y H:i');
    }
}
