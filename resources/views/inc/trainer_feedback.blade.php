@extends('layouts.master')

@section('title')
New Feedback
@endsection

@section('content')
@include('inc.header', ['title' => 'Leave New Training Team Feedback', 'type' => 'external'])

<div class="container">
    {{ html()->form()->route('saveNewTrainerFeedback')->open() }}
        <div class="row border border-danger rounded">
            <p class="strong text-danger">If you do not fill out your personal information, this feedback is submitted anonymously. You will not be 
                identified and we will be unable to follow-up with you regarding your comments. For additional privacy, you may
                access this form while logged-out at <a href="" alt="external link">https://ztlartcc.org/trainer_feedback/new</a>
                https://ztlartcc.org/trainer_feedback/new</p>
            <div class="col-sm-4 form-group">
                <label for="pilot_name" class="control-label">Your Name: (optional)</label>
                {{ html()->text('pilot_name', null)->placeholder('Your Name')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="pilot_email" class="control-label">Your Email: (optional)</label>
                {{ html()->email('pilot_email', null)->placeholder('Your Email')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="pilot_cid" class="control-label">Your VATSIM CID: (optional)</label>
                {{ html()->text('pilot_cid', null)->placeholder('Your VATSIM CID')->class(['form-control']) }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 form-group">
                <label for="feedback_id" class="control-label">Training Team Member:</label>
                {{ html()->select('feedback_id', $feedbackOptions, $controllerSelected)->placeholder('Select Team Member')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="feedback_id" class="control-label">Date:</label>
                <div class="input-group date dt_picker_date" id="datetimepicker1" data-target-input="nearest">
                        {{ html()->text('date', null)->placeholder('MM/DD/YYYY')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker1']) }}
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
            </div>
            <div class="col-sm-4 form-group">
                <label for="service" class="control-label">Service Level:</label>
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
                <label for="position" class="control-label">Position Trained/Training Session ID:</label>
                {{ html()->text('position', null)->placeholder('Position Staffed')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="position" class="control-label">Booking Method:</label>
                {{ html()->text('position', null)->placeholder('Position Staffed')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="position" class="control-label">Training Method (theory/classroom/sweatbox/live):</label>
                {{ html()->text('position', null)->placeholder('Position Staffed')->class(['form-control']) }}
            </div>
        </div>
        <div class="row form-group">
            <label for="comments" class="control-label">Additional Comments:</label>
            {{ html()->textarea('comments', null)->placeholder('Additional Comments')->class(['form-control']) }}
        </div>
        <div class="g-recaptcha" data-sitekey="{{ config('google.site_key') }}"></div>
        <br>
        <button class="btn btn-success mb-2" type="submit">Send Feedback</button>
    {{ html()->form()->close() }}
</div>
@endsection
