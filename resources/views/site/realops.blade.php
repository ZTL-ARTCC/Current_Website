@extends('layouts.master')

@section('title')
Realops
@endsection

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container py-4">
        @if(auth()->guard('realops')->guest())
            <a href="/realops/login" class="btn btn-primary float-right">Login as Realops Pilot</a>
        @else
            <button disabled class="btn btn-primary float-right">Welcome, {{ auth()->guard('realops')->user()->full_name }}</button>
        @endif
        <h2>Realops</h2>
    </div>
</span>
<br>

<div class="container">
<p>Welcome to the main page for ZTL's Realops event! Realops events simulate actual traffic flow by encouraging pilot to fly real-world routes flown by actual airlines on a real time schedule. Use the controls below to bid on a flight and manage your event participation. Thanks for flying with ZTL!</p>

<div class="row">
    <div class="col-md-8">
        <h3>Schedule</h3>
    </div>
    <div class="col-md-4">
    {!! Form::open(['action' => 'RealopsController@index', 'method' => 'GET']) !!}
        <div class="mb-3 input-group">
            {!! Form::text('filter', $airport_filter, ['class' => 'form-control', 'placeholder' => 'Filter by Airport']) !!}
            <div class="input-group-append">
                <button class="btn btn-outline-success" type="submit">Filter</button>
            </div>
        </div>
    {!! Form::close() !!}
    </div>
</div>
@if(count($flights) > 0)
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th scope="col">Date</td>
            <th scope="col">Flight Number</th>
            <th scope="col">Departure Time (ET)</th>
            <th scope="col">Departure Airport</th>
            <th scope="col">Arrival Airport</th>
            <th scope="col">Estimated Arrival Time (ET)</th>
            <th scope="col">Route</th>
            <th scope="col">Bidding Status</th>
            @if(auth()->guard('realops')->check() && toggleEnabled('realops_bidding'))
                <th scope="col">Actions</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($flights as $f)
            <tr>
                <td>{{ $f->flight_date_formatted }}</td>
                <td>{{ $f->flight_number }}</td>
                <td>{{ $f->dep_time_formatted }}</td>
                <td>{{ $f->dep_airport }}</td>
                <td>{{ $f->arr_airport }}</td>
                @if($f->est_arr_time)
                    <td>{{ $f->est_arr_time_formatted }}</td>
                @else
                    <td>N/A</td>
                @endif
                @if($f->route)
                    <td>{{ $f->route }}</td>
                @else
                    <td>N/A</td>
                @endif
                <td>
                    @if($f->assigned_pilot)
                        @if(auth()->guard('realops')->check() && auth()->guard('realops')->id() == $f->assigned_pilot_id)
                            <p>
                                <span class="badge badge-success">Assigned to You</span>
                                @unlesstoggle('realops_bidding')
                                    <a href="/realops/cancel-bid" class="btn btn-danger btn-sm d-block mt-2">Cancel Bid</a>
                                @endtoggle
                            </p>
                        @else
                            <p><span class="badge badge-secondary">Assigned</span></p>
                        @endif
                    @elseif(toggleEnabled('realops_bidding'))
                        <p><span class="badge badge-success">Open For Bidding</span></p>
                    @else
                        <p><span class="badge badge-warning">Bidding Closed, No Assignment</span></p>
                    @endif
                </td>
                @if(auth()->guard('realops')->check() && toggleEnabled('realops_bidding'))
                    <td>
                        <center>
                            @if(auth()->guard('realops')->user()->assigned_flight)
                                @if(auth()->guard('realops')->user()->assigned_flight->id == $f->id)
                                    <a href="/realops/cancel-bid" class="btn btn-danger btn-sm">Cancel Bid</a>
                                @elseif(! $f->assigned_pilot)
                                    <button class="btn btn-success btn-sm" disabled>Bid</button>
                                @endif
                            @elseif(! $f->assigned_pilot)
                                <a href="/realops/bid/{{ $f->id }}" class="btn btn-success btn-sm">Bid</a>
                            @endif
                        </center>
                    </td>
                @endif
            </tr>
        </div>
        @endforeach
    </tbody>
</table>
<div class="float-right">
    {!! $flights->appends(request()->query())->links() !!}
</div>
@else
    <div class="card mb-3">
        <div class="card-body text-center">
            <h4>No Flights</h4>
            <p>There are no flights to display matching that search criteria. Please try adjusting your airport filter or wait for more flights to be added</p>
            <h4 class="fa fa-plane"></h4>
        </div>
    </div>
@endif
</div>
@endsection
