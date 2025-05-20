@extends('layouts.dashboard')

@section('title')
Training Statistics
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ mix('css/clipboard.css') }}" />
@endpush

@section('content')
@include('inc.header', ['title' => 'Training Department Dashboard'])

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" data-target="#general" aria-controls="general" aria-selected="true" href="#">General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" data-target="#monthly" aria-controls="monthly" aria-selected="false" href="#monthly">Monthly Stats</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" data-target="#tickets" aria-controls="tickets" aria-selected="false" href="#tickets">Ticket Views</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" data-target="#configuration" aria-controls="configuration" aria-selected="false" href="#configuration">Configuration</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <livewire:ta-stats-general>
                </div>
                <div class="tab-pane fade" id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
                    <livewire:ta-stats-monthly lazy>
                </div>
                <div class="tab-pane fade" id="tickets" role="tabpanel" aria-labelledby="tickets-tab">
                    <i class="fa-solid fa-triangle-person-digging fa-10x m-5"></i>
                    <h3 class="text-center">Under Construction</h3>
                </div>
                <div class="tab-pane fade" id="configuration" role="tabpanel" aria-labelledby="configuration-tab">
                    <i class="fa-solid fa-triangle-person-digging fa-10x m-5"></i>
                    <h3 class="text-center">Under Construction</h3>
                </div>
            </div>
        </div>
    </div>
    @endsection