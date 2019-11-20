@extends('layouts.master')

@section('title')
Airports
@endsection

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Airports</h2>
        &nbsp;
    </div>
</span>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            {!! Form::open(['action' => 'FrontController@searchAirport']) !!}
                <div class="form-inline">
                    {!! Form::text('apt', null, ['placeholder' => 'Search Airport (ICAO)', 'class' => 'form-control']) !!}
                    &nbsp;
                    <button class="btn btn-success" type="submit">Search</button>
                </div>
            {!! Form::close() !!}
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-4">
        </div>
    </div>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Airport Name</th>
                <th scope="col">Latest METAR</th>
                <th scope="col">Visual Conditions</th>
            </tr>
            @foreach($airports as $a)
                <tr>
                    <td><a href="/pilots/airports/view/{{ $a->id }}">{{ $a->name }} ({{ $a->ltr_3 }})</a></td>
                    <td>{{ $a->metar }}</td>
                    <td>{{ $a->visual_conditions }}</td>
                </tr>
            @endforeach
        </thead>
    </table>
</div>
@endsection
