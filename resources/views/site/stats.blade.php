@extends('layouts.master')

@section('title')
Stats
@endsection

@section('content')
<span class="border border-light view-header">
    <div class="container">
        <h2>ARTCC Controller Statistics</h2>
    </div>
</span>
<br>

<?php
$mname = date("F", mktime(0, 0, 0, $month, 1, $year));
if ($month == 1) { $pm = 12; $pyr = $year - 1; } else { $pm = $month -1; $pyr = $year; }
if ($month == 12) { $nm = 1; $nyr = $year + 1; } else { $nm = $month + 1; $nyr = $year; }
?>

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <center>
                <h3>Total Hours this Month</h3>
            </center>
            <div class="card" style="background-color:#d3d3d3">
                <br>
                <center><h4>{{ number_format($all_stats['month'], 2) }}</h4></center>
                <br>
            </div>
        </div>
        <div class="col-sm-4">
            <center>
                <h3>Total Hours this Year</h3>
            </center>
            <div class="card" style="background-color:#d3d3d3">
                <br>
                <center><h4>{{ number_format($all_stats['year'], 2) }}</h4></center>
                <br>
            </div>
        </div>
        <div class="col-sm-4">
            <center>
                <h3>Total Hours All Time</h3>
            </center>
            <div class="card" style="background-color:#d3d3d3">
                <br>
                <center><h4>{{ number_format($all_stats['total'], 2) }}</h4></center>
                <br>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-2">
            <a class="btn btn-primary" href="/controllers/stats/<?=$pyr?>/<?=$pm?>"><i class="fa fa-arrow-left"></i> Previous Month</a></li>
        </div>
        <div class="col-sm-8">
            <center><h4>Showing Stats for <?=$mname?> 20<?=$year?></h4></center>
        </div>
        <div class="col-sm-2" align="right">
            <a class="btn btn-primary" href="/controllers/stats/<?=$nyr?>/<?=$nm?>">Next Month <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
    <br>
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link tab-link active" href="#home" role="tab" data-toggle="tab">Home Controllers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link tab-link" href="#visit" role="tab" data-toggle="tab">Visiting Controllers</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Local</th>
                        <th scope="col">TRACON</th>
                        <th scope="col">Center</th>
                        <th scope="col">Total This Month</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($home as $h)
                        <tr>
                            <td>{{ $h->full_name }}</td>
                            <td>{{ $h->rating_short }}</td>
                            <td>{{ $stats[$h->id]->local_hrs }}</td>
                            <td>{{ $stats[$h->id]->approach_hrs }}</td>
                            <td>{{ $stats[$h->id]->enroute_hrs }}</td>
                            @if($stats[$h->id]->total_hrs >= 1)
                                <td class="hours-success black"><b>{{ $stats[$h->id]->total_hrs }}</b></td>
                            @else
                                <td class="hours-danger black"><b>{{ $stats[$h->id]->total_hrs }}</b></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="visit">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Local</th>
                        <th scope="col">TRACON</th>
                        <th scope="col">Center</th>
                        <th scope="col">Total This Month</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visit as $h)
                        <tr>
                            <td>{{ $h->full_name }}</td>
                            <td>{{ $h->rating_short }}</td>
                            <td>{{ $stats[$h->id]->local_hrs }}</td>
                            <td>{{ $stats[$h->id]->approach_hrs }}</td>
                            <td>{{ $stats[$h->id]->enroute_hrs }}</td>
                            @if($stats[$h->id]->total_hrs >= 1)
                                <td class="hours-success black"><b>{{ $stats[$h->id]->total_hrs }}</b></td>
                            @else
                                <td class="hours-danger black"><b>{{ $stats[$h->id]->total_hrs }}</b></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
