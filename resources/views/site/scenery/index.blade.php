@extends('layouts.master')

@section('title')
Scenery
@endsection

@section('content')
@include('inc.header', ['title' => 'Scenery', 'type' => 'external'])

@include('inc.scenery_index')
@endsection
