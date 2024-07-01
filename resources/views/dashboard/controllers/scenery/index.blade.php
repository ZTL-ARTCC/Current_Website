@extends('layouts.dashboard')

@section('title')
Scenery
@endsection

@section('content')
@include('inc.header', ['title' => 'Scenery'])

@include('inc.scenery_index')
@endsection
