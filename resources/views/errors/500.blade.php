@extends('layouts.master')

@section('title')
Internal Server Error
@endsection

@php
/**
    * Checks exception messages for POST/GET variables and removes them. Prevents dangerous things like environment variables
    * from leaking while ensuring a meaningful and safe message can be displayed to the user (and reported to the dev team).
*/
function safeErrorMessage($exception) {
    $safeErrorMessage = 'Unspecified error';
    $exceptionMessage = $exception->getMessage();
    if (preg_match('/(https?:\/\/.*[\?\&])/', $exceptionMessage)) {
        $safeErrorMessage = preg_replace('/(https?:\/\/.*[\?\&][^\s]+)/', '---', $exceptionMessage);
    } else {
        $safeErrorMessage = $exceptionMessage;
    }
        return $safeErrorMessage;
}
@endphp

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>500: Internal Server Error</h2>
        &nbsp;
    </div>
</span>
<br>

<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Something just happened that wasn't supposed to happen and we're really not sure why! Please kindly report this error to <a href="mailto:wm@ztlartcc.org">wm@ztlartcc.org</a> with descriptive steps to reproduce and a link to the page. Thank you for your help!</h3>
                    <br>
                    <h3>Please include the following message in your email (if applicable):</h3>
                    <p>{{ safeErrorMessage($exception) }}</p>
                </div>
                <div class="col-sm-4">
                    <img src="/photos/this_is_fine.png" width="300px">
                </div>
            </div>
            <hr>
            <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Go Back</a>
            <a href="/" class="btn btn-primary"><i class="fas fa-home"></i> Go Home</a>
            @if(Auth::check())
                <a href="/dashboard" class="btn btn-info"><i class="fas fa-tachometer-alt"></i> Controller Dashboard</a>
            @endif
        </div>
    </div>
</div>

@endsection
