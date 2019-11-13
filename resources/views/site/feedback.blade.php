@extends('layouts.master')

@section('title')
New Feedback
@endsection

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Leave New Feedback</h2>
        &nbsp;
    </div>
</span>
<br>

<div class="container">
    {!! Form::open(['action' => 'FrontController@saveNewFeedback']) !!}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('controller', 'Controller:', ['class' => 'control-label']) !!}
                    {!! Form::select('controller', $controllers, null, ['placeholder' => 'Select Controller', 'class' => 'form-control']) !!}
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
        <div class="g-recaptcha" data-sitekey="6LcC3XoUAAAAAG8ST6HXqS3_reIZRLcA09sDdodw"></div>
        <br>
        <button class="btn btn-success" type="submit">Send Feedback</button>
    {!! Form::close() !!}
</div>
@endsection
