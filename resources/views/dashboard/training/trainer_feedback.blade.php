@extends('layouts.dashboard')

@section('title')
Training Team Feedback
@endsection

@push('custom_header')
@vite('resources/assets/sass/trainingticket.scss')
@endpush

@section('content')
@include('inc.header', ['title' => 'Leave New Training Team Feedback'])
@include('inc.trainer_feedback', ['redirect' => 'internal'])
@endsection
