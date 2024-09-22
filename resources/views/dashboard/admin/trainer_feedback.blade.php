@extends('layouts.dashboard')

@section('title')
Training Team Feedback Management
@endsection

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
    'data_var' => 'p_feedback'
];
$tabs[] = (object) $t;
?>
<div class="container">
    <ul class="nav nav-tabs nav-justified" role="tablist">
        @foreach($tabs as $t)
        <li class="nav-item">
            <a class="nav-link tab-link {{ $t->active }}" href="#{{ $t->id }}" role="tab" data-toggle="tab">{{ ucfirst($t->id) }} Feedback</a>
        </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($tabs as $t)
        <div role="tabpanel" class="tab-pane {{ $t->active }}" id="{{ $t->id }}">
            @if($$t->data_var->count() > 0)
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
                    @foreach($$t->data_var as $f)
                    <tr>
                        <td>{{ $f->feedback_name }}</td>
                        <td>{{$f->student_name}} ({{$f->student_cid}}), {{$f->student_email}}</td>
                        <td data-toggle="tooltip" title="{{ $f->comments }}">{{ str_limit($f->comments, 80, '...') }}</td>
                        <td>{{ $f->created_at }}</td>
                        <td>
                            <span data-toggle="modal" data-target="#saveFeedback{{ $f->id }}">
                                <button type="button" class="btn btn-success simple-tooltip" data-placement="top" data-toggle="tooltip" title="Save Feedback"><i class="fas fa-check"></i></button>
                            </span>
                            @if($t->id == 'new')
                            <span data-toggle="modal" data-target="#hideFeedback{{ $f->id }}">
                                <button type="button" class="btn btn-danger simple-tooltip" data-placement="top" data-toggle="tooltip" title="Hide Feedback"><i class="fas fa-times"></i></button>
                            </span>
                            @endif
                            @if($f->student_email != null)
                            <span data-toggle="modal" data-target="#emailFeedback{{ $f->id }}">
                                <button type="button" class="btn btn-warning simple-tooltip" data-placement="top" data-toggle="tooltip" title="Email Student"><i class="fas fa-envelope"></i></button>
                            </span>
                            @endif
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
            @else
            <p>No {{ $t->id }} feedback.</p>
            @endif
            @if($t->id == 'processed')
            {!! $$t->data_var->links() !!}
            @endif
        </div>
        @endforeach
    </div>
</div>
<!-- MODALS -->
<div class="modal fade" id="saveFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="feedbackSaveDetail" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Save Feedback for {{ $f->feedback_name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{ html()->form()->route('saveTrainerFeedback', [$f->id])->open() }}
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="feedback_id">Training Team Member</label>
                        {{ html()->select('feedback_id', $feedbackOptions, $f->feedback_id)->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        <label for="position">Service Level</label>
                        {{ html()->text('service_level', $f->service_level)->class(['form-control']) }}
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
                        {{ html()->text('booking_method', $f->booking_method)->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        <label for="position">Training Method</label>
                        {{ html()->text('training_method', $f->training_method)->class(['form-control']) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label for="feedback_id">Student Name</label>
                        {{ html()->text('student_name', $f->student_name)->class(['form-control']) }}
                    </div>
                    <div class="col-sm-4">
                        <label for="position">Student CID</label>
                        {{ html()->text('student_cid', $f->student_cid)->class(['form-control']) }}
                    </div>
                    <p class="text-danger">*Student information is not shared with the training team member</p>
                </div>
                <br>
                <label for="pilot_comments">Student Comments</label>
                {{ html()->textarea('comments', $f->comments)->class(['form-control']) }}
                <br>
                <label for="staff_comments">Staff Comments</label>
                {{ html()->textarea('staff_comments', $f->staff_comments)->class(['form-control']) }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{ html()->form()->route('hideTrainerFeedback', [$f->id])->open() }}
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="feedback_id">Training Team Member</label>
                        {{ html()->select('feedback_id', $feedbackOptions, $f->feedback_id)->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        <label for="position">Service Level</label>
                        {{ html()->text('service_level', $f->service_level)->class(['form-control']) }}
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
                        {{ html()->text('booking_method', $f->booking_method)->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        <label for="position">Training Method</label>
                        {{ html()->text('training_method', $f->training_method)->class(['form-control']) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label for="feedback_id">Student Name</label>
                        {{ html()->text('student_name', $f->student_name)->class(['form-control']) }}
                    </div>
                    <div class="col-sm-4">
                        <label for="position">Student CID</label>
                        {{ html()->text('student_cid', $f->student_cid)->class(['form-control']) }}
                    </div>
                    <p class="text-danger">*Student information is not shared with the training team member</p>
                </div>
                <br>
                <label for="pilot_comments">Student Comments</label>
                {{ html()->textarea('comments', $f->comments)->class(['form-control']) }}
                <br>
                <label for="staff_comments">Staff Comments</label>
                {{ html()->textarea('staff_comments', $f->staff_comments)->class(['form-control']) }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button action="submit" class="btn btn-success">Hide Feedback</button>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
</div>

<div class="modal fade" id="updateFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Feedback for {{ $f->feedback_name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{ html()->form()->route('updateFeedback', [$f->id])->open() }}
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="feedback_id">Controller</label>
                        {{ html()->select('feedback_id', $feedbackOptions, $f->feedback_id)->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        <label for="position">Position</label>
                        {{ html()->text('position', $f->position)->class(['form-control']) }}
                    </div>
                </div>
                <br>
                <label for="pilot_comments">Pilot Comments</label>
                {{ html()->textarea('pilot_comments', $f->comments)->class(['form-control']) }}
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button action="submit" class="btn btn-success">Update Feedback</button>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
</div>


<div class="modal fade" id="emailFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="feedbackDetailEmail" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Student, {{ $f->pilot_name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button action="submit" class="btn btn-success">Send Email</button>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
</div>
@endsection
