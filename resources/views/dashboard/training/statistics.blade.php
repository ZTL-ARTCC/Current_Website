@extends('layouts.dashboard')

@section('title')
Training Statistics
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ asset('css/clipboard.css') }}" />
@endpush

@section('content')
@include('inc.header', ['title' => 'Training Department Dashboard'])

<div class="container">
    <div class="row">
        <div class="col-sm-2 col-xs-12">
            <div class="card">
                {{ html()->form('GET')->route('statistics')->open() }}
                @php ($yearOfMonthsLookback = array())
                @foreach(array_reverse(Carbon\CarbonPeriod::create(now()->subMonths(11), '1 month', now())->toArray()) as $date)
                @php ($yearOfMonthsLookback[$date->format('m Y')] = $date->format('M Y'))
                @endforeach
                {{ html()->select('date_select', $yearOfMonthsLookback, implode(' ', $stats['dateSelect']))->class(['form-control'])->attributes(['onchange'=>"this.form.submit();"]) }}
                {{ html()->form()->close() }}
            </div>
            <div class="card text-center mt-2">
                <div class="card-header">Sessions Per Month</div>
                <div class="card-body">
                    <h2>{{ $stats['sessionsPerMonth'] }}</h2>
                </div>
            </div>
            <div class="card text-center mt-2">
                <div class="card-header">Staff Ins/Mtr</div>
                <div class="card-body">
                    <h2>{{ $stats['totalInstructors'] }} / {{ $stats['totalMentors'] }}</h2>
                </div>
            </div>
            <div class="card text-center mt-2">
                <div class="card-header">Unique Students</div>
                <div class="card-body">
                    <h2>{{ $stats['uniqueStudents'] }}</h2>
                </div>
            </div>
            <div class="card text-center mt-2">
                <div class="card-header">OTS Pass/Fail</div>
                <div class="card-body">
                    <h2><span class="text-success">{{ $stats['otsPerMonth']['pass'] }}</span> / <span class="text-danger">{{ $stats['otsPerMonth']['fail'] }}</span></h2>
                </div>
            </div>
            <div class="card text-center mt-2">
                <div class="card-header">Student<br />No-Shows</div>
                <div class="card-body">
                    <h2>{{ $stats['sessionsPerMonthNoShow'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-sm-5 col-xs-12">
            <div class="card">
                {{ html()->img('/dashboard/training/statistics/graph?id=1&year=' . $stats['dateSelect']['year'] . '&month=' . $stats['dateSelect']['month'], 'Sessions per month') }}
            </div>
            <br />
            <div class="card">
                {{ html()->img('/dashboard/training/statistics/graph?id=2&year=' . $stats['dateSelect']['year'] . '&month=' . $stats['dateSelect']['month'], 'Sessions by instructor and type') }}
            </div>
        </div>
        <div class="col-sm-5 col-xs-12">
            <div class="card">
                {{ html()->img('/dashboard/training/statistics/graph?id=3&year=' . $stats['dateSelect']['year'] . '&month=' . $stats['dateSelect']['month'], 'Average session duration') }}
            </div>
            <br />
            <div class="card">
                {{ html()->img('/dashboard/training/statistics/graph?id=4&year=' . $stats['dateSelect']['year'] . '&month=' . $stats['dateSelect']['month'], 'Students requiring training') }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-header">TA's Monthly Report
                    <div class="float-right"><i class="fas fa-clipboard" onclick="copyToClipboard('taReport');" title="Copy to clipboard"></i></div>
                </div>
                <div class="card-body">
                    <textarea id="taReport" rows="3" class="form-control" readonly>{{ $stats['taMonthlyReport'] }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
