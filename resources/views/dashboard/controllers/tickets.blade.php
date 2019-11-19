@extends('layouts.dashboard')

@section('title')
Training Tickets
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Training Tickets for {{ Auth::user()->full_name }}</h2>
    &nbsp;
</div>
<br>

@endsection