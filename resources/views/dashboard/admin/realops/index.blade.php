@extends('layouts.dashboard')

@section('title')
    Realops Management
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Realops Management</h2>
    &nbsp;
</div>
<br>
<div class="container">
<div class="mb-4">
    <a href="/dashboard/admin/realops/create" class="btn btn-success mr-2">Add Flight</a>
    <span data-toggle="modal" data-target="#upload">
        <button type="button" class="btn btn-warning mr-2" data-toggle="tooltip">Bulk Upload Flights</button>
    </span>
    <span data-toggle="modal" data-target="#dump">
        <button type="button" class="btn btn-danger" data-toggle="tooltip">Dump all Data</button>
    </span>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Flight Number</th>
            <th scope="col">Departure Time (ET)</th>
            <th scope="col">Departure Airport</th>
            <th scope="col">Arrival Airport</th>
            <th scope="col">Estimated Arrival Time (ET)</th>
            <th scope="col">Route</th>
            <th scope="col" colspan="2">Assigned Pilot</th>
            <th scope="col" style="width: 15%">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($flights as $f)
            <tr>
                <td>{{ $f->flight_date_formatted }}</td>
                <td>{{ $f->flight_number }}</td>
                <td>{{ $f->dep_time_formatted }}</td>
                <td>{{ $f->dep_airport }}</td>
                <td>{{ $f->arr_airport }}</td>
                @if($f->est_arr_time)
                    <td>{{ $f->est_arr_time_formatted }}</td>
                @else
                    <td>N/A</td>
                @endif
                @if($f->route)
                    <td>{{ $f->route }}</td>
                @else
                    <td>N/A</td>
                @endif
                @if($f->assigned_pilot)
                    <td>
                        {{ $f->assigned_pilot->full_name }}
                        <br/>
                        <span class="small">({{ $f->assigned_pilot->email }})</span>
                    </td>
                    <td>
                        <a href="/dashboard/admin/realops/remove-pilot/{{ $f->id }}" class="btn btn-danger btn-sm float-right" title="Unassign Pilot" data-toggle="tooltip"><i class="fas fa-times"></i></a>
                    </td>
                @else
                    <td colspan="2">N/A</td>
                @endif
                <td>
                <a href="/dashboard/admin/realops/edit/{{ $f->id }}" class="btn btn-warning btn-sm float-left mr-2" title="Edit" data-toggle="tooltip"><i class="fas fa-pencil-alt"></i></a>
                <span data-toggle="modal" data-target="#assign{{ $f->id }}">
                    <button type="button" class="btn btn-success btn-sm float-left mr-2" data-toggle="tooltip" title="Assign Pilot"><i class="fas fa-plus"></i></button>
                </span>
                <a href="/dashboard/admin/realops/delete/{{ $f->id }}" class="btn btn-danger btn-sm float-left mr-2" title="Delete" data-toggle="tooltip"><i class="fas fa-times"></i></a>
                </td>
            </tr>
            <div class="modal fade" id="assign{{ $f->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Assign Pilot for {{ $f->flight_number }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        {!! Form::open(['action' => ['RealopsController@assignPilotToFlight', $f->id], 'method' => 'PUT']) !!}
                        @csrf
                        <div class="modal-body">
                        {!! Form::select('pilot', $pilots, null, ['placeholder' => 'Select Pilot', 'class' => 'form-control']) !!}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button action="submit" class="btn btn-success">Assign</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </tbody>
</table>
<div class="float-right">
    {!! $flights->links() !!}
</div>
</div>
<div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Upload Flights</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['action' => 'RealopsController@bulkUploadFlights', 'files' => 'true']) !!}
            @csrf
            <div class="modal-body">
                {!! Form::label('file', 'Upload CSV File of Flights') !!}
                {!! Form::file('file', ['class' => 'form-control', 'accept' => '.csv']) !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button action="submit" class="btn btn-success">Upload</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="dump" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dump all Realops Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['action' => 'RealopsController@dumpData']) !!}
            @csrf
            <div class="modal-body">
                <p>Danger zone! This will dump all realops data and there is no way to reverse this action. Type <b>confirm - dump all</b> to proceed.</p>
                {!! Form::text('confirm_text', null, ['class' => 'form-control', 'placeholder' => 'confirm - dump all']) !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button action="submit" class="btn btn-danger">Continue</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
