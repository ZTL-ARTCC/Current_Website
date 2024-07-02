@extends('layouts.dashboard')

@section('title')
    Realops Management
@endsection

@section('content')
@include('inc.header', ['title' => 'Add Realops Flight'])

<div class="container">
    {{ html()->form()->route('createFlight', ['id' => 'realops_add_edit_flight'])->open() }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label for="flight_date" class="form-label">Date</label>
                    <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                        {{ html()->text('flight_date', null)->placeholder('MM/DD/YYYY')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepicker']) }}
                        <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="flight_number">Flight Number</label>
                    {{ html()->text('flight_number', null)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-3">
                    <label for="gate">Gate</label>
                    {{ html()->text('gate', null)->class(['form-control'])->placeholder('Optional') }}
                </div>
                <div class="col-sm-3">
                    <label for="dep_time">Departure Time</label>
                    {{ html()->text('dep_time', null)->class(['form-control'])->placeholder('HH:MM - Required')->id('realops_add_edit_dep_time') }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label for="dep_airport">Departure Airport</label>
                    {{ html()->text('dep_airport', null)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-4">
                    <label for="arr_airport">Arrival Airport</label>
                    {{ html()->text('arr_airport', null)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-4">
                    <label for="est_time_enroute">Estimated Time Enroute (ETE)</label>
                    {{ html()->text('est_time_enroute', null)->class(['form-control'])->placeholder('HH:MM - Optional')->id('realops_add_edit_ete') }}
                </div>
            </div>
        </div>
        <button class="btn btn-success mr-2" type="button" onclick="realopsValidateAndSubmit();">Submit</button>
        <a class="btn btn-danger" href="/dashboard/admin/realops">Cancel</a>
    {{ html()->form()->close() }}
</div>
<script src="{{asset('js/realops.js')}}"></script>
@endsection
