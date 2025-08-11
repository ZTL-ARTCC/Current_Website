@extends('layouts.dashboard')

@section('title')
Training Team Feedback Management
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ mix('css/trainingticket.css') }}" />
@endpush

@section('content')
@include('inc.header', ['title' => 'Training Team Feedback Management'])

<?php
$tabs = [];
$t = [
    'active' => ' active',
    'id' => 'new',
    'data_var' => 'feedback'
];
$tabs[] = (object) $t;
$t = [
    'active' => '',
    'id' => 'processed',
    'data_var' => 'feedback_p'
];
$tabs[] = (object) $t;
?>
<div class="container">
    <ul class="nav nav-tabs nav-justified" role="tablist">
        @foreach($tabs as $t)
        <li class="nav-item">
            <a class="nav-link tab-link {{ $t->active }}" href="#{{ $t->id }}" role="tab" data-bs-toggle="tab">{{ ucfirst($t->id) }} Feedback</a>
        </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($tabs as $t)
        <div role="tabpanel" class="tab-pane {{ $t->active }}" id="{{ $t->id }}">
            @php $d = (string)$t->data_var; @endphp
            @if($$d->count() > 0)
            <table class="table table-outline">
                <thead>
                    <tr>
                        <th scope="col">INS/MTR</th>
                        <th scope="col">Student</th>
                        <th scope="col">Comments</th>
                        <th scope="col">Submitted</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($$d as $f)
                    <tr>
                        <td>{{ $f->feedback_name }}</td>
                        @php
                            $student_info = 'Anonymous';
                            if ($f->student_name != '' || $f->student_cid != '' || $f->student_email != '') {
                                $name = ($f->student_name != '') ? $f->student_name : 'No Name';
                                $cid = ($f->student_cid != '') ? '(' . $f->student_cid . ')' : '';
                                $email = ($f->student_email != '') ? ', ' . $f->student_email : '';
                                $student_info = $name . ' ' . $cid . ' ' . $email;
                            }
                        @endphp
                        <td>{{$student_info}}</td>
                        <td data-bs-toggle="tooltip" title="{{ $f->comments }}">{{ str_limit($f->comments, 80, '...') }}</td>
                        <td>{{ $f->created_at }}</td>
                        <td>
                            @if($t->id == 'new')
                            <span data-bs-toggle="modal" data-bs-target="#saveFeedback{{ $f->id }}">
                                <button type="button" class="btn btn-success simple-tooltip" data-bs-placement="top" bs-toggle="tooltip" title="Save Feedback"><i class="fas fa-check"></i></button>
                            </span>
                            <span data-bs-toggle="modal" data-bs-target="#hideFeedback{{ $f->id }}">
                                <button type="button" class="btn btn-danger simple-tooltip" data-bs-placement="top" data-bs-toggle="tooltip" title="Hide Feedback"><i class="fas fa-times"></i></button>
                            </span>
                            @else
                            <span data-bs-toggle="modal" data-bs-target="#updateFeedback{{ $f->id }}">
                                <button type="button" class="btn btn-success simple-tooltip" data-bs-placement="top" data-bs-toggle="tooltip" title="Update Feedback"><i class="fas fa-pencil-alt"></i></button>
                            </span>
                            @endif
                            @if($f->student_email != null)
                            <span data-bs-toggle="modal" data-bs-target="#emailFeedback{{ $f->id }}">
                                <button type="button" class="btn btn-warning simple-tooltip" data-bs-placement="top" data-bs-toggle="tooltip" title="Email Student"><i class="fas fa-envelope"></i></button>
                            </span>
                            @endif
                        </td>
                    </tr>
                    <!-- MODALS -->
                    @if($t->id == 'new')
                    <div class="modal fade" id="saveFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="feedbackSaveDetail" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Save Feedback</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                {{ html()->form()->route('saveTrainerFeedback', [$f->id])->open() }}
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="feedback_id">Training Team Member</label>
                                            {{ html()->select('trainer_id', $feedbackOptions, $f->trainer_id)->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="position">Service Level</label>
                                            {{ html()->select('service_level', [
                        5 => 'Excellent',
                        4 => 'Good',
                        3 => 'Fair',
                        2 => 'Poor',
                        1 => 'Unsatisfactory'
                    ], $f->service_level)->class(['form-control']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="feedback_id">Training Date</label>
                                            {{ html()->text('feedback_date', $f->feedback_date)->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="position">Position Trained/Lesson ID</label>
                                            {{ html()->text('position_trained', $f->position_trained)->class(['form-control']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="feedback_id">Booking Method</label>
                                            {{ html()->select('booking_method', [0=>'Scheddy', 1=>'Ad-Hoc'], $f->booking_method)->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="position">Training Method</label>
                                            {{ html()->select('training_method', [0=>'Theory', 1=>'Sweatbox', 2=>'Live Network'], $f->training_method)->class(['form-control']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="feedback_id">Student Name</label>
                                            {{ html()->text('student_name', $f->student_name)->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="position">Student CID</label>
                                            {{ html()->text('student_cid', $f->student_cid)->class(['form-control']) }}
                                        </div>
                                        <p class="text-danger mx-3">*Student Name/CID are not shared with the training team member</p>
                                    </div>
                                    <br>
                                    <label for="pilot_comments">Student Comments</label>
                                    {{ html()->textarea('comments', $f->comments)->class(['form-control']) }}
                                    <br>
                                    <label for="staff_comments">Staff Comments</label>
                                    {{ html()->textarea('staff_comments', $f->staff_comments)->class(['form-control']) }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button action="submit" class="btn btn-success">Save Feedback</button>
                                </div>
                                {{ html()->form()->close() }}
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="hideFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="feedbackDetailHide" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Hide Feedback for {{ $f->feedback_name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                {{ html()->form()->route('hideTrainerFeedback', [$f->id])->open() }}
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="feedback_id">Training Team Member</label>
                                            {{ html()->select('trainer_id', $feedbackOptions, $f->trainer_id)->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="position">Service Level</label>
                                            {{ html()->select('service_level', [
                        5 => 'Excellent',
                        4 => 'Good',
                        3 => 'Fair',
                        2 => 'Poor',
                        1 => 'Unsatisfactory'
                    ], $f->service_level)->class(['form-control']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="feedback_id">Training Date</label>
                                            {{ html()->text('feedback_date', $f->feedback_date)->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="position">Position Trained/Lesson ID</label>
                                            {{ html()->text('position_trained', $f->position_trained)->class(['form-control']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="feedback_id">Booking Method</label>
                                            {{ html()->select('booking_method', [0=>'Scheddy', 1=>'Ad-Hoc'], $f->booking_method)->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="position">Training Method</label>
                                            {{ html()->select('training_method', [0=>'Theory', 1=>'Sweatbox', 2=>'Live Network'], $f->training_method)->class(['form-control']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="feedback_id">Student Name</label>
                                            {{ html()->text('student_name', $f->student_name)->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="position">Student CID</label>
                                            {{ html()->text('student_cid', $f->student_cid)->class(['form-control']) }}
                                        </div>
                                        <p class="text-danger mp-2">*Student Name/CID are not shared with the training team member</p>
                                    </div>
                                    <br>
                                    <label for="pilot_comments">Student Comments</label>
                                    {{ html()->textarea('comments', $f->comments)->class(['form-control']) }}
                                    <br>
                                    <label for="staff_comments">Staff Comments</label>
                                    {{ html()->textarea('staff_comments', $f->staff_comments)->class(['form-control']) }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button action="submit" class="btn btn-success">Hide Feedback</button>
                                </div>
                                {{ html()->form()->close() }}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="modal fade" id="updateFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Feedback for {{ $f->feedback_name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                {{ html()->form()->route('updateTrainerFeedback', [$f->id])->open() }}
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="feedback_id">Training Team Member</label>
                                            {{ html()->select('trainer_id', $feedbackOptions, $f->trainer_id)->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="position">Service Level</label>
                                            {{ html()->select('service_level', [
                        5 => 'Excellent',
                        4 => 'Good',
                        3 => 'Fair',
                        2 => 'Poor',
                        1 => 'Unsatisfactory'
                    ], $f->service_level)->class(['form-control']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="feedback_id">Training Date</label>
                                            {{ html()->text('feedback_date', $f->feedback_date)->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="position">Position Trained/Lesson ID</label>
                                            {{ html()->text('position_trained', $f->position_trained)->class(['form-control']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="feedback_id">Booking Method</label>
                                            {{ html()->select('booking_method', [0=>'Scheddy', 1=>'Ad-Hoc'], $f->booking_method)->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="position">Training Method</label>
                                            {{ html()->select('training_method', [0=>'Theory', 1=>'Sweatbox', 2=>'Live Network'], $f->training_method)->class(['form-control']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="feedback_id">Student Name</label>
                                            {{ html()->text('student_name', $f->student_name)->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="position">Student CID</label>
                                            {{ html()->text('student_cid', $f->student_cid)->class(['form-control']) }}
                                        </div>
                                        <p class="text-danger mx-3">*Student Name/CID are not shared with the training team member</p>
                                    </div>
                                    <br>
                                    <label for="pilot_comments">Student Comments</label>
                                    {{ html()->textarea('comments', $f->comments)->class(['form-control']) }}
                                    <br>
                                    <label for="staff_comments">Staff Comments</label>
                                    {{ html()->textarea('staff_comments', $f->staff_comments)->class(['form-control']) }}
                                </div>
                                <div class="modal-footer">
                                    {{ html()->select('status', [
                                            0 => 'N/A',
                                            1 => 'Saved',
                                            2 => 'Hidden'
                                        ], $f->status)->class(['form-control']) }}
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button action="submit" class="btn btn-success">Update Feedback</button>
                                </div>
                                {{ html()->form()->close() }}
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($f->student_email != null)
                    <div class="modal fade" id="emailFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="feedbackDetailEmail" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Email Student, {{ $f->student_name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                {{ html()->form()->route('emailFeedback', [$f->id])->open() }}
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="name">Your Name</label>
                                            {{ html()->text('name', 'vZTL Training Staff')->class(['form-control']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="email">Your Email</label>
                                            {{ html()->email('email', 'ta@ztlartcc.org')->class(['form-control']) }}
                                        </div>
                                    </div>
                                    <br>
                                    <label for="subject">Subject</label>
                                    {{ html()->text('subject', 'An email regarding your recent feedback')->class(['form-control']) }}
                                    <br>
                                    <label for="body">Message</label>
                                    {{ html()->textarea('body', null)->placeholder('Enter your reply to the student here')->class(['form-control']) }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button action="submit" class="btn btn-success">Send Email</button>
                                </div>
                                {{ html()->form()->close() }}
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </tbody>
            </table>
            @elseif($t->id == 'new')
                @include('inc.empty_state', ['header' => 'No New Feedback', 'body' => 'There is no new feedback to show.', 'icon' => 'fa-solid fa-file'])
            @elseif($t->id == 'processed')
                @include('inc.empty_state', ['header' => 'No Processed Feedback', 'body' => 'There is no processed feedback to show.', 'icon' => 'fa-solid fa-file'])
            @endif
            @if($t->id == 'processed')
            {!! $$d->links() !!}
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
