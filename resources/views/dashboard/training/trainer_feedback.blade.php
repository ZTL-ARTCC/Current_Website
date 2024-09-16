@extends('layouts.dashboard')

@section('title')
Training Team Feedback
@endsection

@section('content')
@include('inc.header', ['title' => 'Training Team Feedback'])
@include('inc.trainer_feedback')
@endsection
