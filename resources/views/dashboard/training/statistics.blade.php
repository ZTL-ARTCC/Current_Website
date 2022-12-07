@extends('layouts.dashboard')

@section('title')
Training Statistics
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Training Department Dashboard</h2>
    &nbsp;
</div>
<br>
@if(Auth::user()->isAbleTo('snrStaff'))
<div class="container">
    <div class="row">
        <div class="col-2">
            <div class="card">
                {!! Form::open(['action' => ['TrainingDash@statistics'], 'method' => 'get']) !!}
                @php ($yearOfMonthsLookback = array())
                @foreach(array_reverse(Carbon\CarbonPeriod::create(now()->subMonths(11), '1 month', now())->toArray()) as $date)
                @php ($yearOfMonthsLookback[$date->format('m Y')] = $date->format('M Y'))
                @endforeach
                {!! Form::select('date_select', $yearOfMonthsLookback, implode(' ', $stats['dateSelect']), ['class' => 'form-control', 'onchange'=>"this.form.submit();"]) !!}
                {!! Form::close() !!}
            </div>
            <div class="card text-center">
                <div class="card-header">Sessions Per Month</div>
                <div class="card-body">
                    <h2>{{ $stats['sessionsPerMonth'] }}</h2>
                </div>
            </div>
            <div class="card text-center">
                <div class="card-header">Unique Students</div>
                <div class="card-body">
                    <h2>{{ $stats['uniqueStudents'] }}</h2>
                </div>
            </div>
            <div class="card text-center">
                <div class="card-header">OTS Pass/Fail</div>
                <div class="card-body">
                    <h2><span class="text-success">{{ $stats['otsPerMonth']['pass'] }}</span> / <span class="text-danger">{{ $stats['otsPerMonth']['fail'] }}</span></h2>
                </div>
            </div>
            <div class="card text-center">
                <div class="card-header">Student<br />Cancellations</div>
                <div class="card-body">
                    <h2>{{ $stats['studentCancel'] }}</h2>
                </div>
            </div>
            <div class="card text-center">
                <div class="card-header">Student<br />No-Shows</div>
                <div class="card-body">
                    <h2>{{ $stats['studentNoShow'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                {{ Html::image('/dashboard/training/statistics/graph?id=1&year=' . $stats['dateSelect']['year'] . '&month=' . $stats['dateSelect']['month'], 'Sessions per month') }}
            </div>
            <br />
            <div class="card">
                {{ Html::image('/dashboard/training/statistics/graph?id=2&year=' . $stats['dateSelect']['year'] . '&month=' . $stats['dateSelect']['month'], 'Average session duration') }}
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                {{ Html::image('/dashboard/training/statistics/graph?id=3&year=' . $stats['dateSelect']['year'] . '&month=' . $stats['dateSelect']['month'], 'Sessions by instructor and type') }}
            </div>
            <br />
            <div class="card">
                {{ Html::image('/dashboard/training/statistics/graph?id=4&year=' . $stats['dateSelect']['year'] . '&month=' . $stats['dateSelect']['month'], 'Students requiring training') }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">TA's Monthly Report
                    <div class="float-right"><i class="fas fa-clipboard" onclick="copyToClipboard('taReport');"></i></div>
                </div>
                <div class="card-body">
                    <textarea id="taReport" rows="3" class="form-control">{{ $stats['taMonthlyReport'] }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<h3>Insufficient permissions to view this page</h3>
@endif

@endsection