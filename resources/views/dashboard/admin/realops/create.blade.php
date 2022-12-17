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
                    {!! Form::label('flight_date', 'Date') !!}
                    {!! Form::text('flight_date', null, ['class' => 'form-control', 'placeholder' => 'MM/DD/YYYY - Required']) !!}
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
                <div class="col-sm-6">
                    {!! Form::label('dep_airport', 'Departure Airport') !!}
                    {!! Form::text('dep_airport', null, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('arr_airport', 'Arrival Airport') !!}
                    {!! Form::text('arr_airport', null, ['class' => 'form-control', 'placeholder' => 'Required']) !!}
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
@endsection
