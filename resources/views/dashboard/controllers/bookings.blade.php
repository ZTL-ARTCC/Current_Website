@extends('layouts.dashboard')

@section('title')
ATC Bookings
@endsection

@section('content')
@include('inc.header', ['title' => 'ATC Bookings'])

<div class="row">
    <div class="col-lg-6">
        @if(count($bookings) == 0)
            <div class="card mb-3">
                <div class="card-body text-center">
                    <h4>No ATC Bookings</h4>
                    <p>There are no ATC Bookings at this time. Create the first one using the form to the right!</p>
                    <h4 class="fa fa-clock"></h4>
                </div>
            </div>
        @else
            @foreach($bookings as $date => $date_bookings)
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th colspan="2">{{ $date }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($date_bookings as $b)
                            <tr>
                                <td>
                                    <strong>{{ $b->callsign }}</strong>
                                    &emsp;
                                    <span>{{ $b->start_time_formatted }} - {{ $b->end_time_formatted }}Z</span>
                                    @if($b->cid == Auth::id() || Auth::user()->can('snrStaff'))
                                        <span class="float-right"><a href="/dashboard/controllers/bookings/delete/{{ $b->id }}" class="btn btn-sm btn-danger">Cancel</a></span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @endif
        <small class="d-block mb-3"><i>All of your bookings are listed above, but you can only see bookings made by others 14 days in advance.</i></small>
        <small class="d-block"><i>Bookings allow pilots to plan flights within ATC coverage. This system does not reserve a controller position for a single person. Please refer to the facility general policy for more information.</i></small>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header pb-0">
                <h5>New Booking</h5>
                <small class="d-block mb-3"><i>Bookings are limited by ZTL policy to TWR positions and above</i></small>
            </div>
            <div class="card-body">
                {{ html()->form()->route('createBooking') }}
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="facility">3-Letter Facility Identifier</label>
                                {{ html()->text('facility', null)->placeholder('i.e. ATL, CLT, ZTL')->class(['form-control']) }}
                            </div>
                            <div class="col-md-6">
                                <label for="position">Position</label>
                                {{ html()->text('position', null)->placeholder('i.e. TWR, APP, CTR')->class(['form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="start">Start Time (ZULU)</label>
                                <div class="input-group date dt_picker_datetime" id="datetimepickerstart" data-target-input="nearest">
                                    {{ html()->text('start', null)->placeholder('MM/DD/YYYY 00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepickerstart']) }}
                                    <div class="input-group-append" data-target="#datetimepickerstart" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="end">End Time (ZULU)</label>
                                <div class="input-group date dt_picker_datetime" id="datetimepickerend" data-target-input="nearest">
                                    {{ html()->text('end', null)->placeholder('MM/DD/YYYY 00:00')->class(['form-control', 'datetimepicker-input'])->attributes(['data-target' => '#datetimepickerend']) }}
                                    <div class="input-group-append" data-target="#datetimepickerend" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        @if(count($types) == 1)
                            {{ html()->hidden('type', 'booking') }}
                        @else
                            <label for="type">Booking Type</label>
                            {{ html()->select('type', $types, 'booking')->class(['form-control']) }}
                        @endif
                    </div>
                    <p><i><b>All times are in ZULU time</b></i></p>
                    <p><i>ZULU Time Now: {{ Carbon\Carbon::now()->format('H:i') }}</i></p>
                    <div>
                        <button class="btn btn-success float-left mr-2" type="submit">Create Booking</button>
                        <button class="btn btn-danger" type="submit">Cancel</button>
                    </div>
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
</div>

@endsection
