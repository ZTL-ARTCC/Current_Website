@extends('layouts.email')

@section('content')
    <p>Dear {{ $visitor->name }},</p>

    <p>{!! nl2br(e($visitor->reject_reason)) !!}</p>

    <p>If you have any questions, please contact the DATM at <a href="mailto:datm@ztlartcc.org">datm@ztlartcc.org</a>.</p>
    <br>

    <p>Best regards,</p>
    <p>ZTL Visiting Staff</p>
@endsection
