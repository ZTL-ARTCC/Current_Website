@extends('layouts.master')

@section('title')
Airports
@endsection

@section('content')
@include('inc.header', ['title' => 'Airports', 'type' => 'external'])

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            {{ html()->form()->route('searchAirport')->open() }}
                <div class="form-inline">
                    {{ html()->text('apt', null)->placeholder('Search Airport (ICAO)')->class(['form-control']) }}
                    &nbsp;
                    <button class="btn btn-success" type="submit">Search</button>
                </div>
            {{ html()->form()->close() }}
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
