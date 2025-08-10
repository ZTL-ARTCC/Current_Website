@extends('layouts.dashboard')

@section('title')
New Incident Report
@endsection

@section('content')
@include('inc.header', ['title' => 'New Incident Report'])

<div class="container">
    <p>Please use this to report incidents rather than sending an email. Please know that your ID and the controller's ID will be recorded for the sole reason of resolving any issues. Once any issues have been resolved, the incident will be archived without the controller/report ID.</p>
    {{ html()->form()->route('submitIncidentReport')->open() }}
        @csrf
        <div class="form-group mb-3">
            <div class="row">
                <div class="col-sm-4">
                    <label for="controller_id" class="form-label">Controller</label>
                    {{ html()->select('controller_id', $controllers, null)->placeholder('Select Controller')->class(['form-control']) }}
                </div>
                <div class="col-sm-4">
                    <label for="time" class="form-label">Time of Incident (Zulu)</label>
                    <div class="input-group date dt_picker_time" id="datetimepicker2" data-td-target-input="nearest" data-td-target-toggle="nearest">
                        {{ html()->text('time', null)->placeholder('00:00')->class(['form-control datetimepicker-input'])->attributes(['data-td-target' => '#datetimepicker2']) }}
                        <span class="input-group-text" data-td-target="#datetimepicker2" data-td-toggle="datetimepicker">
                            <i class="fas fa-clock"></i>
                        </span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <label for="date" class="form-label">Date of Incident</label>
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-td-target-input="nearest" data-td-target-toggle="nearest">
                        {{ html()->text('date', null)->placeholder('MM/DD/YYYY')->class(['form-control','datetimepicker-input'])->attributes(['data-td-target' => '#datetimepicker1']) }}
                        <span class="input-group-text" data-td-target="#datetimepicker1" data-td-toggle="datetimepicker">
                            <i class="fas fa-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <div class="row">
                <div class="col-sm-4">
                    <label for="controller_callsign" class="form-label">Controller Callsign</label>
                    {{ html()->text('controller_callsign', null)->placeholder('Callsign of the Controller')->class(['form-control']) }}
                </div>
                <div class="col-sm-4">
                    <label for="reporter_callsign" class="form-label">Your Callsign</label>
                    {{ html()->text('reporter_callsign', null)->placeholder('Your Callsign')->class(['form-control']) }}
                </div>
                <div class="col-sm-4">
                    <label for="aircraft_callsign" class="form-label">Aircraft Callsign (If Applicable)</label>
                    {{ html()->text('aircraft_callsign', null)->placeholder('Aircraft Involved (Optional)')->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="description" class="form-label">Description of Incident</label>
            {{ html()->textarea('description', null)->placeholder('Please describe the incident as descriptively as possible.')->class(['form-control']) }}
        </div>
        <button action="submit" class="btn btn-success">Submit</button>
    {{ html()->form()->close() }}
</div>
@endsection
