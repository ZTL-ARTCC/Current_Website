@extends('layouts.dashboard')

@section('title')
View Bookings
@endsection

@section('content')
@include('inc.header', ['title' => 'View Bookings'])

<p>Please remember that these do not guarantee ATC service and do not prevent someone other than the booker to be signed on for the position at the booked time</p>

<p> Callisgn Start End</p>
@foreach($bookings as $booking)
<p>{{ $booking->callsign }} {{ $booking->start }} {{ $booking->end }}</p>
@endforeach

@endsection
