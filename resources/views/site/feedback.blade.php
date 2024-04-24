@extends('layouts.master')

@section('title')
New Feedback
@endsection

@section('content')
@include('inc.header', ['title' => 'Leave New Feedback', 'type' => 'external'])

<div class="container">
    {{ html()->form()->route('FrontController@saveNewFeedback') }}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {{ html()->label('Event or Controller:', 'feedback_id')->class(['control-label']) }}
                    {{ html()->select('feedback_id', $feedbackOptions, $controllerSelected)->placeholder('Select Event or Controller')->class(['form-control']) }}
                </div>
                <div class="form-group">
                    {{ html()->label('Service Level:', 'service')->class(['control-label']) }}

                    {{ html()->select('service', [
                        0 => 'Excellent',
                        1 => 'Good',
                        2 => 'Fair',
                        3 => 'Poor',
                        4 => 'Unsatisfactory'
                    ], null)->placeholder(Select Level)->class(['form-control']) }}
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            {{ html()->label('Pilot Name:', 'pilot_name')->class('control-label']) }}
                            {{ html()->text('pilot_name', null)->placeholder('Your Name')->class(['form-control']) }}
                        </div>
                        <div class="col-sm-6">
                            {{ html()->label('Pilot Email:', 'pilot_email')->class('control-label']) }}
                            {{ html()->email('pilot_email', null)->placeholder('Your Email')->class(['form-control']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {{ html()->label('Position:', 'position')->class('control-label']) }}
                    {{ html()->text('position', null)->placeholder('Position Staffed')->class(['form-control']) }}
                </div>
                <div class="form-group">
                    {{ html()->label('Flight Callsign:', 'callsign')->class('control-label']) }}
                    {{ html()->text('callsign', null)->placeholder('Your Flight Callsign')->class(['form-control']) }}
                </div>
                <div class="form-group">
                    {{ html()->label('Pilot VATSIM CID:', 'pilot_cid')->class('control-label']) }}
                    {{ html()->text('pilot_cid', null)->placeholder('Your VATSIM CID')->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            {{ html()->label('Additional Comments:', 'comments')->class('control-label']) }}
            {{ html()->textarea('comments', null)->placeholder('Additional Comments')->class(['form-control']) }}
        </div>
        <div class="g-recaptcha" data-sitekey="{{ config('google.site_key') }}"></div>
        <br>
        <button class="btn btn-success mb-2" type="submit">Send Feedback</button>
    {{ html()->form()->close() }}
</div>
@endsection
