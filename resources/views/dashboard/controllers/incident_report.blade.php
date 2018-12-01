@extends('layouts.dashboard')

@section('title')
New Incident Report
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>New Incident Report</h2>
    &nbsp;
</div>
<br>

<div class="container">
    <p>Please use this to report incidents rather than sending an email. Please know that your ID and the controller's ID will be recorded for the sole reason of resolving any issues. Once any issues have been resolved, the incident will be archived without the controller/report ID.</p>
    {!! Form::open(['action' => 'ControllerDash@submitIncidentReport']) !!}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    {!! Form::label('controller_id', 'Controller', ['class' => 'form-label']) !!}
                    {!! Form::select('controller_id', $controllers, null, ['placeholder' => 'Select Controller', 'class' => 'form-control']) !!}
                </div>
                <div class="col-sm-4">
                    {!! Form::label('time', 'Time of Incident (Zulu)', ['class' => 'form-label']) !!}
                    {!! Form::time('time', null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-4">
                    {!! Form::label('date', 'Date of Incident', ['class' => 'form-label']) !!}
                    {!! Form::date('date', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('description', 'Description of Incident', ['class' => 'form-label']) !!}
            {!! Form::textArea('description', null, ['placeholder' => 'Please describe the incident as descriptively as possible.', 'class' => 'form-control']) !!}
        </div>
        <button action="submit" class="btn btn-success">Submit</button>
    {!! Form::close() !!}
</div>
@endsection
