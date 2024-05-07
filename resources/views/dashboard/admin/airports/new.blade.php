@extends('layouts.dashboard')

@section('title')
    New Airport
@endsection

@section('content')
@include('inc.header', ['title' => 'New Airport'])

<div class="container">
    {{ html()->form()->route('AdminDash@storeAirport') }}
        @csrf
        <div class="form-group">
            <label for="name">Airport Name</label>
            {{ html()->text('name', null)->class(['form-control']) }}
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="FAA">3-Letter Identifier (FAA)</label>
                    {{ html()->text('FAA', null)->class(['form-control']) }}
                </div>
                <div class="col-sm-6">
                    <label for="ICAO">4-Letter Identifier</label>
                    {{ html()->text('ICAO', 'K')->class(['form-control']) }}
                </div>
            </div>
        </div>
        <button class="btn btn-success" type="submit">Add</button>
    {{ html()->form()->close() }}
</div>
@endsection
