<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingLesson extends Model {
    protected $fillable = [
        'lesson_id',
        'lesson_name',
        'course_id',
        'instructor_qualification',
        'sort_category_id',
        'proficiency_advance',
        'solo',
        'certification',
        'ots',
        'moodle_prerequisite',
        'soi_link',
        'active',
        'edited_by'
    ];

    public function getAssociatedRatingAttribute(): null|int {
        $course = TrainingCourse::find($this->course_id);
        if (is_int($course->associated_rating)) {
            return intval($course->associated_rating);
        }
        return null;
    }

    public function getSortCategoryAttribute(): null|string {
        $category = TrainingSortCategory::find($this->sort_category_id);
        if (is_string($category->category_name)) {
            return $category->category_name;
        }
        return null;
    }
}
