@extends('layouts.dashboard')

@section('title')
    Edit Realops Flight
@endsection

@section('content')
@include('inc.header', ['title' => 'Edit Realops Flight'])

<div class="container">
    {{ html('PUT')->form()->route('RealopsController@editFlight', [$flight->id])->id('realops_add_edit_flight') }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    {{ html()->label('Date', 'flight_date')->class(['form-label']) }}
                    <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                        {{ html()->text('flight_date', $flight->flight_date_formatted)->placeholder('MM/DD/YYYY')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker') }}
                        <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    {{ html()->label('Flight Number', 'flight_number') }}
                    {{ html()->text('flight_number', $flight->flight_number)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-3">
                    {{ html()->label('Gate', 'gate') }}
                    {{ html()->text('gate', $flight->gate)->class(['form-control'])->placeholder('Optional') }}
                </div>
                <div class="col-sm-3">
                    {{ html()->label('Departure Time', 'dep_time') }}
                    {{ html()->text('dep_time', $flight->dep_time_formatted)->class(['form-control'])->placeholder('HH:MM - Required')->id('realops_add_edit_dep_time') }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    {{ html()->label('Departure Airport', 'dep_airport') }}
                    {{ html()->text('dep_airport', $flight->dep_airport)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-4">
                    {{ html()->label('Arrival Airport', 'arr_airport') }}
                    {{ html()->text('arr_airport', $flight->arr_airport)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-4">
                    {{ html()->label('Estimated Time Enroute (ETE)', 'est_time_enroute') }}
                    {{ html()->text('est_time_enroute', $flight->est_time_enroute_formatted)->class(['form-control'])->placeholder('HH:MM - Optional')->id('realops_add_edit_ete') }}
                </div>
            </div>
        </div>
        <button class="btn btn-success mr-2" type="button" onclick="realopsValidateAndSubmit();">Submit</button>
        <a class="btn btn-danger" href="/dashboard/admin/realops">Cancel</a>
    {{ html()->form()->close() }}
</div>
<script src="{{asset('js/realops.js')}}">
@endsection
