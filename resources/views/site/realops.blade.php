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
    <p>Welcome to the main page for ZTL's Realops!</p>
</div>
@endsection
