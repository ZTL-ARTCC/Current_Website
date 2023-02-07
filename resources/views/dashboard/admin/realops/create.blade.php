@extends('layouts.dashboard')

@section('title')
    Realops Management
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Add Realops Flight</h2>
    &nbsp;
</div>
<br>
<div class="container">
    {!! Form::open(['action' => 'RealopsController@createFlight']) !!}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    {!! Form::label('flight_date', 'Date', ['class' => 'form-label']) !!}
                    <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                        {!! Form::text('flight_date', null, ['placeholder' => 'MM/DD/YYYY', 'class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker']) !!}
                        <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    {!! Form::label('flight_number', 'Flight Number') !!}
                    {!! Form::text('flight_number', null, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
                </div>
                <div class="col-sm-4">
                    {!! Form::label('dep_time', 'Departure Time') !!}
                    {!! Form::text('dep_time', null, ['class' => 'form-control', 'placeholder' => 'HH:MM - Required']) !!}
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
                    {!! Form::label('est_arr_time', 'Estimated Arrival Time') !!}
                    {!! Form::text('est_arr_time', null, ['class' => 'form-control', 'placeholder' => 'HH:MM - Optional']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('route', 'Route') !!}
            {!! Form::text('route', null, ['class' => 'form-control', 'placeholder' => 'Optional']) !!}
        </div>
        <button class="btn btn-success mr-2" action="submit">Submit</button>
        <a class="btn btn-danger" href="/dashboard/admin/realops">Cancel</a>
    {!! Form::close() !!}
</div>
<script type="text/javascript">
$(function () {
    $('#datetimepicker').datetimepicker({
        format: 'L'
    });
});
</script>
@endsection
