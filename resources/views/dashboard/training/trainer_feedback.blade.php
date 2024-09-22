@extends('layouts.dashboard')

@section('title')
Training Team Feedback
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ mix('css/trainingticket.css') }}" />
<script src="{{mix('js/trainingticket.js')}}"></script>
@endpush

@section('content')
@include('inc.header', ['title' => 'Leave New Training Team Feedback'])
@include('inc.trainer_feedback')
@endsection
