@extends('layouts.master')

@section('title')
Stats
@endsection

@section('content')
@include('inc.header', ['title' => 'ARTCC Controller Statistics', 'type' => 'external'])
@include('inc.stats', ['privacy' => true])
@endsection
