@extends('layouts.dashboard')

@section('title')
View Incident Report
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h3>View Incident Report</h3>
    &nbsp;
</div>
<br>
<div class="container">
    <a href="/dashboard/admin/incident" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
    <br><br>

    <div class="card">
        <div class="card-header">
            <h3>
                Incident at {{ $incident->time }} on {{ $incident->date }}
            </h3>
        </div>
        <div class="card-body">
            <p><b>Controller:</b> <i>{{ $incident->controller_name }}</i></p>
            <p><b>Controller Callsign:</b> <i>{{ $incident->controller_callsign }}</i></p>
            <p><b>Reporter:</b> <i>{{ $incident->reporter_name }}</i></p>
            <p><b>Reporter Callsign:</b> <i>{{ $incident->reporter_callsign }}</i></p>
            <p><b>Aircraft Callsign (If Applicable):</b> <i>{{ $incident->aircraft_callsign }}</i></p>
            <p><b>Description:</b></p>
            <p>{!! nl2br($incident->description) !!}</p>
        </div>
    </div>
</div>
@endsection
