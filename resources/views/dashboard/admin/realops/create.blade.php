@extends('layouts.dashboard')

@section('title')
    Realops Management
@endsection

@section('content')
@include('inc.header', ['title' => 'Add Realops Flight'])

<div class="container">
    {!! Form::open(['action' => 'RealopsController@createFlight', 'id' => 'realops_add_edit_flight']) !!}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-3">
                    {!! Form::label('flight_date', 'Date', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                        {!! Form::text('flight_date', null, ['placeholder' => 'MM/DD/YYYY', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker']) !!}
                        <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    {!! Form::label('flight_number', 'Flight Number') !!}
                    {!! Form::text('flight_number', null, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
                </div>
                <div class="col-sm-3">
                    {!! Form::label('gate', 'Gate') !!}
                    {!! Form::text('gate', null, ['class' => 'form-control', 'placeholder' => 'Optional']) !!}
                </div>
                <div class="col-sm-3">
                    {!! Form::label('dep_time', 'Departure Time') !!}
                    {!! Form::text('dep_time', null, ['class' => 'form-control', 'placeholder' => 'HH:MM - Required', 'id' => 'realops_add_edit_dep_time']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    {!! Form::label('dep_airport', 'Departure Airport') !!}
                    {!! Form::text('dep_airport', null, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
                </div>
                <div class="col-sm-4">
                    {!! Form::label('arr_airport', 'Arrival Airport') !!}
                    {!! Form::text('arr_airport', null, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
                </div>
                <div class="col-sm-4">
                    {!! Form::label('est_time_enroute', 'Estimated Enroute Time') !!}
                    {!! Form::text('est_time_enroute', null, ['class' => 'form-control', 'placeholder' => 'HH:MM - Optional', 'id' => 'realops_add_edit_ete']) !!}
                </div>
            </div>
        </div>
        <button class="btn btn-success mr-2" type="button" onclick="realopsValidateAndSubmit();">Submit</button>
        <a class="btn btn-danger" href="/dashboard/admin/realops">Cancel</a>
    {!! Form::close() !!}
</div>
{{Html::script(asset('js/realops.js'))}}
@endsection
