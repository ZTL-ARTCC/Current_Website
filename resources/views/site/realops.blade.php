@extends('layouts.master')

@section('title')
Realops
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ mix('css/realops.css') }}" />
@endpush

@section('content')
@if(auth()->guard('realops')->guest())
    @include('inc.header', ['title' => 'Realops', 'type' => 'external', 'content' => '<a href="/realops/login" class="btn btn-primary float-right">Login as Realops Pilot</a>'])
@else
    @include('inc.header', ['title' => 'Realops', 'type' => 'external', 'content' => '<button disabled class="btn btn-primary float-right">Welcome, ' . auth()->guard('realops')->user()->full_name . '</button>'])
@endif

<div class="container">
<p>Welcome to the main page for ZTL's Realops event! Realops events simulate actual traffic flow by encouraging pilots to fly real-world routes flown by actual airlines on a real time schedule. Use the controls below to bid on a flight and manage your event participation. Thanks for flying with ZTL!</p>

<div class="row">
    <div class="col-12 mx-2">
        <h3>Schedule</h3>
        <p>Filter by any or all of these criteria</p>
    </div>
    <div class="col-md-12 mb-3 mx-3">
        {{ html()->form('GET')->route('realopsIndex')->id('realops_filter')->open() }}
        <div class="row">
            <div class="col-sm-12 col-md p-1">
               {{ html()->text('airport_filter', $airport_filter)->class(['form-control'])->placeholder('Airport (DEN)')->id('airport_filter') }}
            </div>
            <div class="col-sm-12 col-md p-1">
               {{ html()->text('flightno_filter', $flightno_filter)->class(['form-control'])->placeholder('Flight (DAL367)')->id('flightno_filter') }}
            </div>
            <div class="col-sm-12 col-md p-1">
               {{ html()->text('date_filter', $date_filter)->class(['form-control'])->placeholder('Date (YYYY-MM-DD)')->id('date_filter') }}
            </div>
            <div class="col-sm-12 col-md p-1">
               {{ html()->text('time_filter', $time_filter)->class(['form-control'])->placeholder('Time (11:00)')->id('time_filter') }}
            </div>
            <div class="col-sm-12 col-md p-1 mr-2 text-center">
                <button class="btn btn-success mr-2" type="button" onclick="realopsFilterValidateAndSubmit();" title="Filter"><i class="fas fa-filter"></i>&nbsp;Filter</button>
                <a href="/realops" class="btn btn-warning" title="Clear"><i class="fas fa-redo"></i>&nbsp;Clear</a>
            </div>
        </div>
        {{ html()->form()->close() }}
    </div>
</div>
@if(count($flights) > 0)
<table class="table table-bordered table-striped text-center">
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Callsign<br><small class="text-muted">Flight Number</small></th>
            <th scope="col">Departure Time (UTC)</th>
            <th scope="col">Departure Airport</th>
            <th scope="col">Arrival Airport</th>
            <th scope="col">Estimated Enroute Time (HH:MM)</th>
            <th scope="col">Gate</th>
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
                <td class="airline-cell">
                    <img src="{{ $f->getImageDirectory() }}" class="airline-logo">
                    @if($f->callsign)
                       {{ $f->callsign }}
                    @else
                        {{ $f->flight_number }}
                    @endif
                    <br>
                    <small class="text-muted">{{ $f->flight_number }}</small>
                </td>
                <td>{{ $f->dep_time_formatted }}</td>
                <td>{{ $f->dep_airport }}</td>
                <td>{{ $f->arr_airport }}</td>
                @if($f->est_time_enroute)
                    <td>{{ $f->est_time_enroute_formatted }}</td>
                @else
                    <td>N/A</td>
                @endif
                @if($f->gate)
                    <td>{{ $f->gate }}</td>
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
                            @if(auth()->guard('realops')->user()->id == $f->assigned_pilot_id)
                                <a href="/realops/cancel-bid/{{ $f->id }}" class="btn btn-danger btn-sm">Cancel Bid</a>
                            @elseif($f->assigned_pilot)
                                    <button class="btn btn-success btn-sm" disabled>Bid</button>
                            @else
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
    @include('inc.empty_state', ['header' => 'No Flights', 'body' => 'There are no flights to display matching that search criteria. Please try adjusting your airport filter or wait for more flights to be added', 'icon' => 'fa fa-plane'])
@endif
</div>
<script src="{{mix('js/realops.js')}}"></script>
@endsection
