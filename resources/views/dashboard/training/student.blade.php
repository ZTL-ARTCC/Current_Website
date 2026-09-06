@extends('layouts.dashboard')

@section('title')
Student Profile
@endsection

@section('content')
@include('inc.header', ['title' => 'Student Profile'])

<div class="container">
    <livewire:student-profile> 
</div>
@endsection