@extends('layouts.dashboard')

@section('title')
Stats
@endsection

@section('content')
@include('inc.header', ['title' => 'ARTCC Controller Statistics'])
@include('inc.stats', ['privacy' => false])
@endsection
