@extends('layouts.dashboard')

@section('title')
Training Tickets
@endsection

@section('content')
<div class="container-fluid view-header">
    <h2>Training Tickets for {{ Auth::user()->full_name }}</h2>
</div>
<br>

@endsection