@extends('layouts.master')

@section('title')
New Feedback
@endsection

@section('content')
@include('inc.header', ['title' => 'Leave New Feedback', 'type' => 'external'])

<div class="container">
    {!! Form::open(['action' => 'FrontController@saveNewFeedback']) !!}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('feedback_id', 'Event or Controller:', ['class' => 'control-label']) !!}
                    {!! Form::select('feedback_id', $feedbackOptions, $controllerSelected, ['placeholder' => 'Select Event or Controller', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('service', 'Service Level:', ['class' => 'control-label']) !!}
                    {!! Form::select('service', [
                        0 => 'Excellent',
                        1 => 'Good',
                        2 => 'Fair',
                        3 => 'Poor',
                        4 => 'Unsatisfactory'
                    ], null, ['placeholder' => 'Select Level', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            {!! Form::label('pilot_name', 'Pilot Name:', ['class' => 'control-label']) !!}
                            {!! Form::text('pilot_name', null, ['placeholder' => 'Your Name', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6">
                            {!! Form::label('pilot_email', 'Pilot Email:', ['class' => 'control-label']) !!}
                            {!! Form::email('pilot_email', null, ['placeholder' => 'Your Email', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('position', 'Position:', ['class' => 'control-label']) !!}
                    {!! Form::text('position', null, ['placeholder' => 'Position Staffed', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('callsign', 'Flight Callsign:', ['class' => 'control-label']) !!}
                    {!! Form::text('callsign', null, ['placeholder' => 'Your Flight Callsign', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('pilot_cid', 'Pilot VATSIM CID:', ['class' => 'control-label']) !!}
                    {!! Form::text('pilot_cid', null, ['placeholder' => 'Your VATSIM CID', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('comments', 'Additional Comments:', ['class' => 'control-label']) !!}
            {!! Form::textArea('comments', null, ['placeholder' => 'Additional Comments', 'class' => 'form-control']) !!}
        </div>
        <div class="g-recaptcha" data-sitekey="{{ config('google.site_key') }}"></div>
        <br>
        <button class="btn btn-success mb-2" type="submit">Send Feedback</button>
    {!! Form::close() !!}
</div>
@endsection
