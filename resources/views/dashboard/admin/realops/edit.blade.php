@extends('layouts.dashboard')

@section('title')
    Edit Realops Flight
@endsection

@section('content')
@include('inc.header', ['title' => 'Edit Realops Flight'])

<div class="container">
    {{ html()->form('PUT')->route('editFlight', [$flight->id])->id('realops_add_edit_flight')->open() }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label for="flight_date" class="form-label-date">Date</label>
                    <div class="input-group date dt_picker_date" id="datetimepicker" data-td-target-input="nearest" data-td-target-toggle="nearest">
                        {{ html()->text('flight_date', $flight->flight_date_formatted)->placeholder('MM/DD/YYYY')->class(['form-control','datetimepicker-input'])->attributes(['data-td-target' => '#datetimepicker']) }}
                        <span class="input-group-text" data-td-target="#datetimepicker1" data-td-toggle="datetimepicker">
                            <i class="fas fa-calendar"></i>
                        </span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <label for="dep_time">Departure Time</label>
                    {{ html()->text('dep_time', $flight->dep_time_formatted)->class(['form-control'])->placeholder('HH:MM - Required')->id('realops_add_edit_dep_time') }}
                </div>
                <div class="col-sm-4">
                    <label for="est_time_enroute">Estimated Time Enroute (ETE)</label>
                    {{ html()->text('est_time_enroute', $flight->est_time_enroute_formatted)->class(['form-control'])->placeholder('HH:MM - Optional')->id('realops_add_edit_ete') }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label for="flight_number">Flight Number</label>
                    {{ html()->text('flight_number', $flight->flight_number)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-4">
                    <label for="callsign">Callsign</label>
                    {{ html()->text('callsign', $flight->callsign)->class(['form-control'])->placeholder('Optional') }}
                </div>
                <div class="col-sm-4">
                    <label for="gate">Gate</label>
                    {{ html()->text('gate', $flight->gate)->class(['form-control'])->placeholder('Optional') }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label for="dep_airport">Departure Airport</label>
                    {{ html()->text('dep_airport', $flight->dep_airport)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-4">
                    <label for="arr_airport">Arrival Airport</label>
                    {{ html()->text('arr_airport', $flight->arr_airport)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-4">
                    <label for="est_time_enroute">Estimated Time Enroute (ETE)</label>
                    {{ html()->text('est_time_enroute', $flight->est_time_enroute_formatted)->class(['form-control'])->placeholder('HH:MM - Required')->id('realops_add_edit_ete') }}
                </div>
            </div>
        </div>
        <button class="btn btn-success me-2" type="button" onclick="realopsValidateAndSubmit();">Submit</button>
        <a class="btn btn-danger" href="/dashboard/admin/realops">Cancel</a>
    {{ html()->form()->close() }}
</div>
<script src="{{mix('js/realops.js')}}"></script>
@endsection
