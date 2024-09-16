@extends('layouts.master')

@section('title')
Training Team Feedback
@endsection

@section('content')
@include('inc.header', ['title' => 'Training Team Feedback', 'type' => 'external'])
@include('inc.trainer_feedback')
@endsection
