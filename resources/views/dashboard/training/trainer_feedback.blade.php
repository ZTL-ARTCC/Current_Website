@extends('layouts.dashboard')

@section('title')
Training Team Feedback
@endsection

@push('custom_header')
@vite('resources/css/trainingticket.css')
@endpush

@section('content')
@include('inc.header', ['title' => 'Leave New Training Team Feedback'])
@include('inc.trainer_feedback', ['redirect' => 'internal'])
@endsection
