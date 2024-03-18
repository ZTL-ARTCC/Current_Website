@extends('layouts.email')

@section('content')
<p>Dear {{ $pilot->full_name }},</p>

<p>This is an automated email to inform you that you have been assigned to Realops flight {{ $flight->flight_number }}. Please review the information below for your flight information and you can also view the latest information on the <a hret="https://www.ztlartcc.org/realops">Realops Dashboard</a>.</p>

<p><b>Date: </b>{{ $flight->flight_date_formatted }}</p>
<p><b>Flight Number: </b>{{ $flight->flight_number }}</p>
<p><b>Departure Time: </b>{{ $flight->dep_time_formatted }}</p>
<p><b>Departure Airport: </b>{{ $flight->dep_airport }}</p>
<p><b>Arrival Airport: </b>{{ $flight->arr_airport }}</p>
<p><b>Estimated Enroute Time: </b>@if($flight->est_time_enroute) {{ $flight->est_time_enroute_formatted }} @else N/A @endif</p>
<p><b>Route: </b>@if($flight->route) {{ $flight->route }} @else N/A @endif</p>
@endsection
