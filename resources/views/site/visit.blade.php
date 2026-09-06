@extends('layouts.master')

@section('title')
Visit
@endsection

@section('content')
@include('inc.header', ['title' => 'Visit ZTL ARTCC', 'type' => 'external'])

<livewire:visit-request>
@endsection
