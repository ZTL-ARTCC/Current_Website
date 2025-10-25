@extends('layouts.master')

@section('title')
Training Team Feedback
@endsection

@push('custom_header')
@vite('resources/assets/sass/trainingticket.scss')
@endpush

@section('content')
@include('inc.header', ['title' => 'Leave New Training Team Feedback', 'type' => 'external'])
@include('inc.trainer_feedback', ['redirect' => 'external'])
@endsection
