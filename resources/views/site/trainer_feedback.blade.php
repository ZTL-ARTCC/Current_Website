@extends('layouts.master')

@section('title')
Training Team Feedback
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ asset('css/trainingticket.css') }}" />
@endpush

@section('content')
@include('inc.header', ['title' => 'Leave New Training Team Feedback', 'type' => 'external'])
@include('inc.trainer_feedback')
@endsection
