@extends('layouts.dashboard')

@section('title')
Training Tickets
@endsection

@section('content')
@include('inc.header', ['title' => 'Training Tickets for {{ Auth::user()->full_name }}'])

@endsection