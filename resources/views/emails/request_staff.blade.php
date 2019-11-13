@extends('layouts.email')

@section('content')
<p>A new request for staffing has been submitted for {{ $date }} at {{ $time }}. Please see the details below.</p>
<br>

<p><b>Name:</b> {{ $name }}</p>
<p><b>Email:</b> {{ $email }}</p>
<p><b>Organization (If Applicable):</b> {{ $org }}</p>
<p><b>Date:</b> {{ $date }}</p>
<p><b>Time:</b> {{ $time }}</p>
<p><b>Additional Information:</b></p>
<p>{!! nl2br(e($exp)) !!}</p>
<br>
@endsection
