@extends('layouts.master')

@section('title')
New Feedback
@endsection

@section('content')
@include('inc.header', ['title' => 'Leave New Training Team Feedback', 'type' => 'external'])

<div class="container">
    {{ html()->form()->route('saveNewTrainerFeedback')->open() }}
        <div class="row border border-danger rounded">
            <div class="col-sm-4 form-group">
                <label for="student_name" class="control-label">Your Name: (optional)</label>
                {{ html()->text('student_name', null)->placeholder('Your Name')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="student_email" class="control-label">Your Email: (optional)</label>
                {{ html()->email('pilot_email', null)->placeholder('Your Email')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="student_cid" class="control-label">Your VATSIM CID: (optional)</label>
                {{ html()->text('pilot_cid', null)->placeholder('Your VATSIM CID')->class(['form-control']) }}
            </div>
            <p class="strong text-danger"><i class="fa-solid fa-shield-halved"></i>&nbsp;&nbsp;This feedback is anonymous if you do not fill out your personal information. You will not be 
                identified and we will be unable to follow-up with you regarding your comments. For additional privacy, you may
                access this form while logged-out at <a href="https://ztlartcc.org/trainer_feedback/new" alt="external link" target="_blank">https://ztlartcc.org/trainer_feedback/new</a></p>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-4 form-group">
                <label for="feedback_id" class="control-label">Training Team Member:</label>
                {{ html()->select('feedback_id', $feedbackOptions)->placeholder('Select Team Member')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="feedback_date" class="control-label">Date of Session/Event:</label>
                <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {{ html()->text('feedback_date', null)->placeholder('MM/DD/YYYY')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker1']) }}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
            </div>
            <div class="col-sm-4 form-group">
                <label for="service_level" class="control-label">Service Level:</label>
                <div id="stars" class="star-input input-group">
                        <span data-rating="1">&star;</span>
                        <span data-rating="2">&star;</span>
                        <span data-rating="3">&star;</span>
                        <span data-rating="4">&star;</span>
                        <span data-rating="5">&star;</span>
                        {{ html()->text('service', null)->attribute('hidden') }}
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 form-group">
                <label for="position_trained" class="control-label">Position Trained/Training Session ID:</label>
                {{ html()->text('position_trained', null)->placeholder('Position Staffed')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="booking_method" class="control-label">Booking Method:</label>
                {{ html()->select('booking_method', [0=>'Easy!Appointments', 1=>'Ad-Hoc'])->placeholder('Pick One')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="training_method" class="control-label">Training Method:</label>
                {{ html()->select('training_method', [0=>'Theory', 1=>'Sweatbox', 2=>'Live Network'])->placeholder('Pick One')->class(['form-control']) }}
            </div>
        </div>
        <div class="row form-group">
            <div class="col-sm-12 form-group">
                <label for="comments" class="control-label">Comments:</label>
                {{ html()->textarea('comments', null)->placeholder('Type your comments here. This will be reviewed by the facility TA and ATA.')->class(['form-control']) }}
            </div>
        </div>
        <div class="g-recaptcha" data-sitekey="{{ config('google.site_key') }}"></div>
        <br>
        <button class="btn btn-success mb-2" type="submit">Send Feedback</button>
    {{ html()->form()->close() }}
</div>
<script src="{{mix('js/trainingticket.js')}}"></script>
@endsection
