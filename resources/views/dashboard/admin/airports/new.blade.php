@extends('layouts.dashboard')

@section('title')
    New Airport
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>New Airport</h2>
    &nbsp;
</div>
<br>
<div class="container">
    {!! Form::open(['action' => 'AdminDash@storeAirport']) !!}
        @csrf
        <div class="form-group">
            {!! Form::label('name', 'Airport Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('FAA', '3-Letter Identifier (FAA)') !!}
                    {!! Form::text('FAA', null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('ICAO', '4-Letter Identifier (ICAO)') !!}
                    {!! Form::text('ICAO', 'K', ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <button class="btn btn-success" type="submit">Add</button>
    {!! Form::close() !!}
</div>
@endsection
