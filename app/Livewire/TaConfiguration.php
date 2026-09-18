<?php

namespace App\Livewire;

use App\TrainingCourse;
use App\TrainingLesson;
use App\TrainingSortCategory;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class TaConfiguration extends Component {
    public array $courses = [];
    public array $categories = [];
    public null|string $lesson_course_id = null;
    public string $course_id;
    public string $course_name;
    public string $lesson_id;
    public string $lesson_name;
    public string $instructor_qualification;
    public null|string $training_category = null;
    public bool $proficiency_advance = false;
    public bool $solo = false;
    public bool $cert = false;
    public bool $ots = false;
    public string $moodle_prerequisite;
    public string $soi_link;
    public bool $active = false;
    public bool $course_certification;
    public null|string $course_link;
    public null|string $course_rating;
    public string $category_name;
    public null|string $scheddy_booking_map;
    public null|string $category_rating;
    public null|string $message;
    public collection $lessons;
    private bool $edit_mode = false;
    public string $last_edited = '';
    
    public function render() {
        $this->fetch_course_list();
        $this->fetch_category_list();
        $this->fetch_lesson_list();
        return view('livewire.ta-configuration');
    }

    public function saveLesson() {
        $validate_lesson_id = 'required|string|alpha_dash|unique:training_lessons';
        if ($this->edit_mode) {
            $validate_lesson_id = 'required|string|alpha_dash';
        }
        $validated = $this->validate([
            'lesson_course_id' => 'required|string|alpha_dash',
            'lesson_id' => $validate_lesson_id,
            'lesson_name' => 'required|string',
            'instructor_qualification' => 'required|integer',
            'training_category' => 'required|string',
            'proficiency_advance' => 'required|boolean',
            'solo' => 'required|boolean',
            'cert' => 'required|boolean',
            'ots' => 'required|boolean',
            'moodle_prerequisite' => 'nullable|integer',
            'soi_link' => 'nullable|URL',
            'active' => 'required|boolean'
        ]);

        $course_id = TrainingCourse::where('course_id', $this->lesson_course_id)->first();

        $lesson = TrainingLesson::updateOrCreate(
            ['lesson_id' => $this->lesson_id],
            [
                'course_id' => $course_id->id,
                'lesson_name' => $this->lesson_name,
                'instructor_qualification' => intval($this->instructor_qualification),
                'training_category' => $this->training_category,
                'proficiency_advance' => $this->proficiency_advance,
                'solo' => $this->solo,
                'certification' => $this->cert,
                'ots' => $this->ots,
                'moodle_prerequisite' => $this->moodle_prerequisite,
                'soi_link' => $this->soi_link,
                'active' => $this->active,
                'edited_by' => Auth::id()
            ]
        );
        $this->resetLessonForm();
    }

    public function saveCourse() {
        $validate_course_id = 'required|string|alpha_dash|unique:training_courses';
        if ($this->edit_mode) {
            $validate_course_id = 'required|string|alpha_dash';
        }
        $validated = $this->validate([
            'course_id' => $validate_course_id,
            'course_name' => 'required|string',
            'course_certification' => 'required|boolean',
            'course_link' => 'nullable|URL',
            'course_rating' => 'required|int',
        ]);

        $course = TrainingCourse::updateOrCreate(
            ['course_id' => $this->course_id],
            [
                'course_name' => $this->course_name,
                'certification' => $this->course_certification,
                'soi_link' => $this->course_link,
                'associated_rating' => $this->course_rating,
            ]
        );
        $this->resetCourseForm();
    }

    public function saveCategory() {
        $validated = $this->validate([
            'category_name' => 'required|string',
            'scheddy_booking_map' => 'nullable|string',
            'category_rating' => 'nullable|int',
        ]);

        $this->training_category = ($this->training_category == '') ?? null;
        $category = TrainingSortCategory::updateOrCreate(
            ['id' => $this->training_category],
            [
                'category_name' => $this->category_name,
                'scheddy_booking_map' => $this->scheddy_booking_map,
                'associated_rating' => $this->category_rating,
            ]
        );
        $this->resetCategoryForm();
    }

    public function resetLessonForm() {
        $input_fields = [
            'lesson_course_id',
            'lesson_id',
            'lesson_name',
            'instructor_qualification',
            'training_category',
            'proficiency_advance',
            'solo',
            'cert',
            'ots',
            'moodle_prerequisite',
            'soi_link',
            'active'
        ];
        $this->reset_form($input_fields);
        $this->active = true;
        $this->edit_mode = false;
        $this->last_edited = '';
    }

    public function resetCourseForm() {
        $input_fields = [
            'course_id',
            'course_name',
            'course_certification',
            'course_link',
            'course_rating'
        ];
        $this->reset_form($input_fields);
        $this->edit_mode = false;
    }

    public function resetCategoryForm() {
        $input_fields = [
            'category_name',
            'scheddy_booking_map',
            'category_rating'
        ];
        $this->reset_form($input_fields);
    }

    private function reset_form(array $input_fields): void {
        foreach ($input_fields as $field) {
            if (in_array(gettype($field), ['string','text'])) {
                $this->$field = '';
            } elseif (gettype($field) == 'int') {
                $this->$field = 0;
            } else {
                $this->$field = null;
            }
        }
    }

    private function fetch_course_list() {
        $this->courses = TrainingCourse::reorder()->orderBy('id', 'asc')->pluck('course_id', 'id')->all();
    }

    private function fetch_category_list() {
        $this->categories = TrainingSortCategory::pluck('category_name', 'id')->all();
    }

    private function fetch_lesson_list() {
        $this->lessons = TrainingLesson::all();
    }

    public function addEditCategory() {
        if (is_null($this->training_category) || strcasecmp($this->training_category, 'null') === 0) {
            $this->resetCategoryForm();
            $this->dispatch('showModal', type: 'Category');
        } else {
            $this->edit_course_lesson('category', $this->training_category);
        }
    }

    public function addEditCourse() {
        if (is_null($this->lesson_course_id) || strcasecmp($this->lesson_course_id, 'null') === 0) {
            $this->resetCourseForm();
            $this->dispatch('showModal', type: 'Course');
        } else {
            $this->edit_course_lesson('course', $this->lesson_course_id);
        }
    }

    public function updatedTrainingCategory() {
        $action = 'add';
        if ($this->training_category != 'null') {
            $action = 'edit';
        }
        $this->dispatch('updateButton', type: 'Category', action: $action);
    }

    public function updatedLessonCourseId() {
        $action = 'add';
        if ($this->lesson_course_id != 'null') {
            $action = 'edit';
        }
        $this->dispatch('updateButton', type: 'Course', action: $action);
    }

    #[On('edit')]
    public function edit_course_lesson(string $item, int $reference) {
        if ($item == 'course') {
            $course = TrainingCourse::find($reference);
            $this->course_id = $course->course_id;
            $this->course_name = $course->course_name;
            $this->course_certification = $course->certification;
            $this->course_link = $course->soi_link;
            $this->course_rating = $course->associated_rating;
            $this->dispatch('showModal', type: ucfirst($item));
        } elseif ($item == 'category') {
            $category = TrainingSortCategory::find($reference);
            $this->category_name = $category->category_name;
            $this->scheddy_booking_map = $category->scheddy_booking_map;
            $this->category_rating = $category->associated_rating;
            $this->dispatch('showModal', type: ucfirst($item));
        } elseif ($item == 'lesson') {
            $lesson = TrainingLesson::find($reference);
            $this->lesson_course_id = TrainingCourse::find($lesson->course_id)->course_id;
            $this->lesson_id = $lesson->lesson_id;
            $this->lesson_name = $lesson->lesson_name;
            $this->instructor_qualification = $lesson->instructor_qualification;
            $this->training_category = $lesson->training_category;
            $this->proficiency_advance = $lesson->proficiency_advance;
            $this->solo = $lesson->solo;
            $this->ots = $lesson->ots;
            $this->moodle_prerequisite = $lesson->moodle_prerequisite;
            $this->soi_link = $lesson->soi_link;
            $this->active = $lesson->active;
            $modify_user = User::find($lesson->edited_by);
            $this->last_edited = 'Last edited by: ' . $modify_user->full_name . ' on: ' . Carbon::parse($lesson->updated_at)->toDayDateTimeString();
        }
    }
}
