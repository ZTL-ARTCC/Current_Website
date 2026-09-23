<div class="text-start">
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-circle-info me-2"></i>SOI Configuration
                </div>
                <div class="card-body">
                    Use the controls below to configure the training syllabus. The syllabus is 
                    organized into courses - each course contains a number of lessons. Some lessons
                    (ex. refresher) may not be organized within a course.
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa-solid fa-folder-tree me-2"></i>SOI
                </div>
                <div class="card-body">
                    <ul>
                        @foreach($courses as $d_course_id => $d_course_name)
                        <li><a href="#" wire:click.prevent="$dispatch('edit', { item: 'course', reference: {{ $d_course_id }} })">{{ $d_course_name }}</a></li>
                        <ul>
                            @php
                            $course_lessons = $lessons->where('course_id', $d_course_id);
                            @endphp
                            @foreach($course_lessons as $d_lesson)
                            <li><a href="#" wire:click.prevent="$dispatch('edit', { item: 'lesson', reference: {{ $d_lesson->id }} })">{{ $d_lesson->lesson_id }}</a>
                                @if(!$d_lesson->active)
                                <i class="fa-solid fa-eye-slash ms-2"></i>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @endforeach
                        <li>No Course Assigned</li>
                        <ul>
                            @php
                            $course_lessons = $lessons->where('course_id', null);
                            @endphp
                            @foreach($course_lessons as $d_lesson)
                            <li><a href="#" wire:click.prevent="$dispatch('edit', { item: 'lesson', reference: {{ $d_lesson->id }} })">{{ $d_lesson->lesson_id }}</a>
                                @if(!$d_lesson->active)
                                <i class="fa-solid fa-eye-slash ms-2"></i>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa-solid fa-person-chalkboard me-2"></i>Create/Edit Lesson
                </div>
                <div class="card-body">
                    @if ($last_edited != '')
                    <div class="mb-3">{{ $last_edited }}</div>
                    @endif
                    <div class="mb-3">
                        <label for="lesson_course_id" class="form-label">Course</label>
                        <div class="input-group">
                            <select class="form-select" wire:model.live="lesson_course_id">
                                <option selected value="null">Leave unassigned, pick one, or click + button to add</option>
                                @foreach($courses as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-success" id="btn_Course" wire:click="addEditCourse" wire:ignore><i class="fa-solid fa-plus" id="btn_icon_Course"></i></button>
                        </div>
                            @error('lesson_course_id')<div class="text-danger text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="lesson_id" class="form-label">Lesson ID <small>(no spaces)</small></label>
                        <input type="input" class="form-control" placeholder="DEL1" wire:model="lesson_id">
                        @error('lesson_id')<div class="text-danger text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="lesson_name" class="form-label">Lesson name</label>
                        <input type="input" class="form-control" placeholder="Clearance Delivery 1" wire:model="lesson_name">
                        @error('lesson_name')<div class="text-danger text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="instructor_qualification" class="form-label">Training staff qualification required to teach this lesson</label>
                        <select class="form-select" aria-label="Required qualification" wire:model="instructor_qualification">
                            <option selected>No restriction</option>
                            <option value="2">S1</option>
                            <option value="3">S2</option>
                            <option value="4">S3</option>
                            <option value="5">C1/C3</option>
                            <option value="8">I1/I3</option>
                        </select>
                        @error('instructor_qualification')<div class="text-danger text-sm">{{ $message }}</div>@enderror                    
                    </div>
                    <div class="mb-3">
                        <label for="soi_link" class="form-label">SOI link <small>(URL to lesson plan or other information)</small></label>
                        <input type="input" class="form-control" placeholder="https://website_link" wire:model="soi_link">
                        @error('soi_link')<div class="text-danger text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="moodle_prerequisite" class="form-label">Moodle pre-requisite <small>(Moodle course ID)</small></label>
                        <input type="input" class="form-control" wire:model="moodle_prerequisite">
                        @error('moodle_prerequisite')<div class="text-danger text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="training_category" class="form-label">Training category</label>
                        <div class="input-group">
                            <select class="form-select" wire:model.live="training_category">
                                <option selected value="null">Leave unassigned, pick one, or click + button to add</option>
                                @foreach($categories as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-success" id="btn_Category" wire:click="addEditCategory" wire:ignore><i class="fa-solid fa-plus" id="btn_icon_Category"></i></button>
                        </div>
                            @error('training_category')<div class="text-danger text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" wire:model="proficiency_advance">
                        <label class="form-check-label" for="proficiency_advance">Proficiency advance? <small>(can lesson be skipped)</small></label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" wire:model="solo">
                        <label class="form-check-label" for="solo">Solo certification possible?</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" wire:model="cert">
                        <label class="form-check-label" for="cert">Position certification possible?</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" wire:model="ots">
                        <label class="form-check-label" for="ots">OTS recommendation possible?</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" wire:model="active">
                        <label class="form-check-label" for="active">Lesson is active?</label>
                    </div>
                    <div class="btn-group" role="group" aria-label="Actions">
                        <button type="button" class="btn btn-success" wire:click="saveLesson" wire:loading.attr="disabled"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Save</button>
                        <button type="button" class="btn btn-warning" wire:click="resetLessonForm" wire:loading.attr="disabled"><i class="fa-solid fa-delete-left me-2"></i>Clear form</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modals -->
    <div class="modal fade" id="createEditCourse" tabindex="-1" aria-labelledby="createEditCourseLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createEditCourseLabel"><i class="fa-solid fa-book me-2"></i>Create/Edit Course</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Course ID <small>(no spaces)</small></label>
                        <input type="input" class="form-control" placeholder="S1" wire:model="course_id">
                        @error('course_id')<div class="text-danger text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="course_name" class="form-label">Course name</label>
                        <input type="input" class="form-control" placeholder="Student 1" wire:model="course_name">
                        @error('course_name')<div class="text-danger text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="course_link" class="form-label">SOI link <small>(URL to course description or other information)</small></label>
                        <input type="input" class="form-control" wire:model="course_link">
                        @error('course_link')<div class="text-danger text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" wire:model.live="course_certification">
                        <label class="form-check-label" for="course_certification">Course results in new certification/rating?</label>
                    </div>
                    <div class="mb-3">
                        <label for="course_rating" class="form-label">New rating at course completion:</label>
                        <select class="form-select" aria-label="Required qualification" wire:model="course_rating" @unless($course_rating_active) disabled @endunless>
                            <option value="0" selected>No rating change</option>
                            <option value="2">S1</option>
                            <option value="3">S2</option>
                            <option value="4">S3</option>
                            <option value="5">C1</option>
                        </select>
                        @error('course_rating')<div class="text-danger text-sm">{{ $message }}</div>@enderror                    
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group" aria-label="Actions">
                        <button type="button" class="btn btn-success" wire:click="saveCourse" wire:loading.attr="disabled"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Save</button>
                        <button type="button" class="btn btn-warning" wire:click="resetCourseForm" wire:loading.attr="disabled"><i class="fa-solid fa-delete-left me-2"></i>Clear form</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createEditCategory" tabindex="-1" aria-labelledby="createEditCategoryLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createEditCategoryLabel"><i class="fa-solid fa-filter me-2"></i>Create/Edit Sort Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category name</label>
                        <input type="input" class="form-control" placeholder="Unrestricted Tower" wire:model="category_name">
                        @error('category_name')<div class="text-danger text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="scheddy_booking_map" class="form-label">Scheddy booking ID</label>
                        <input type="input" class="form-control" placeholder="From Scheddy configuration" wire:model="scheddy_booking_map">
                        @error('scheddy_booking_map')<div class="text-danger text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="category_rating" class="form-label">Associated rating</label>
                        <select class="form-select" aria-label="Required qualification" wire:model="category_rating">
                            <option value ="0" selected>No associated rating</option>
                            <option value="2">S1</option>
                            <option value="3">S2</option>
                            <option value="4">S3</option>
                            <option value="5">C1</option>
                        </select>
                        @error('category_rating')<div class="text-danger text-sm">{{ $message }}</div>@enderror                    
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group" aria-label="Actions">
                        <button type="button" class="btn btn-success" wire:click="saveCategory" wire:loading.attr="disabled"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Save</button>
                        <button type="button" class="btn btn-warning" wire:click="resetCategoryForm" wire:loading.attr="disabled"><i class="fa-solid fa-delete-left me-2"></i>Clear form</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@script
<script>
Livewire.on('showModal', (event) => {
    const modalElement = document.getElementById('createEdit' + event.type);
    const modalInstance = new bootstrap.Modal(modalElement);
    modalInstance.show();
});

Livewire.on('hideModal', (event) => {
    const modalElement = document.getElementById('createEdit' + event.type);
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    if (modalInstance) {
        modalInstance.hide();
    }
});

Livewire.on('updateButton', (event) => {
    const buttonElement = document.getElementById('btn_' + event.type);
    const iconElement = document.getElementById('btn_icon_' + event.type);
    if (event.action == 'add') {
        buttonElement.className = 'btn btn-sm btn-success';
        iconElement.className = 'fa-solid fa-plus';
    }
    else { // edit
        buttonElement.className = 'btn btn-sm btn-warning';
        iconElement.className = 'fa-solid fa-pen-to-square';
    }
    
});
</script>
@endscript
