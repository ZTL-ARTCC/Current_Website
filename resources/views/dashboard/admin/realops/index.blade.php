@extends('layouts.dashboard')

@section('title')
    Realops Management
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ mix('css/realops.css') }}" />
@endpush

@section('content')
@include('inc.header', ['title' => 'Realops Management'])

<div class="container">
<div class="mb-4">
    <a href="/dashboard/admin/realops/create" class="btn btn-success mr-2">Add Flight</a>
    <span data-toggle="modal" data-target="#upload">
        <button type="button" class="btn btn-warning mr-2" data-toggle="tooltip">Bulk Upload Flights</button>
    </span>

@if (Auth::user()->isAbleTo('staff'))
    <span data-toggle="modal" data-target="#dump">
        <button type="button" class="btn btn-danger mr-2" data-toggle="tooltip">Dump all Data</button>
    </span>
    <a href="/dashboard/admin/realops/export" class="btn btn-success">Export Data</a>
@endif

</div>
<table class="table table-bordered table-striped text-center">
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Callsign<br><small class="text-muted">Flight Number</small></th>
            <th scope="col">Departure Time (UTC)</th>
            <th scope="col">Departure Airport</th>
            <th scope="col">Arrival Airport</th>
            <th scope="col">Estimated Enroute Time (HH:MM)</th>
            <th scope="col">Gate</th>
            <th scope="col" colspan="2">Assigned Pilot</th>
            <th scope="col" style="width: 15%">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($flights as $f)
            <tr>
                <td>{{ $f->flight_date_formatted }}</td>
                <td class="airline-cell">
                    <img src="{{ $f->getImageDirectory() }}" class="airline-logo">
                    @if($f->callsign)
                       {{ $f->callsign }}
                    @else
                        {{ $f->flight_number }}
                    @endif
                    <br>
                    <small class="text-muted">{{ $f->flight_number }}</small>
                </td>
                <td>{{ $f->dep_time_formatted }}</td>
                <td>{{ $f->dep_airport }}</td>
                <td>{{ $f->arr_airport }}</td>
                @if($f->est_time_enroute)
                    <td>{{ $f->est_time_enroute_formatted }}</td>
                @else
                    <td>N/A</td>
                @endif
                @if($f->gate)
                    <td>{{ $f->gate }}</td>
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
                    @if(!$f->assigned_pilot)
                        <button type="button" class="btn btn-success btn-sm float-left mr-2" data-toggle="tooltip" title="Assign Pilot"><i class="fas fa-plus"></i></button>
                    @else
                        <button type="button" class="btn btn-success btn-sm float-left mr-2" disabled><i class="fas fa-plus"></i></button>
                    @endif
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
                        {{ html()->form('PUT')->route('assignPilotToFlight', [$f->id])->open() }}
                        @csrf
                        <div class="modal-body">
                        {{ html()->select('pilot', $pilots, null)->placeholder('Select Pilot')->class(['form-control']) }}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button action="submit" class="btn btn-success">Assign</button>
                        </div>
                        {{ html()->form()->close() }}
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
            {{ html()->form()->route('bulkUploadFlights')->acceptsFiles()->open() }}
            @csrf
            <div class="modal-body">
                <label for="file">Upload CSV File of Flights</label>
                {{ html()->file('file')->class(['form-control'])->attributes(['accept' => '.csv']) }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button action="submit" class="btn btn-success">Upload</button>
            </div>
            {{ html()->form()->close() }}
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
            {{ html()->form()->route('dumpData')->open() }}
            @csrf
            <div class="modal-body">
                <p>Danger zone! This will dump all realops data and there is no way to reverse this action. Type <b>confirm - dump all</b> to proceed.</p>
                {{ html()->text('confirm_text', null)->class(['form-control'])->placeholder('confirm - dump all') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button action="submit" class="btn btn-danger">Continue</button>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
</div>
@endsection
