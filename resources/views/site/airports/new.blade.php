@extends('layouts.master')

@section('title')
Add Airport
@endsection

@section('content')
<span class="border border-light view-header">
    <div class="container">
        <h2>Add Airport</h2>
    </div>
</span>
<br>
<div class="container">
    {!! Form::open(['action' => 'FrontController@saveAirport']) !!}
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