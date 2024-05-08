@extends('layouts.dashboard')

@section('title')
Roster Purge
@endsection

@section('content')
@include('inc.header', ['title' => 'Roster Purge Assistant'])

<?php
$mname = date("F", mktime(0, 0, 0, $month, 1, $year));
if ($month == 1) { $pm = 12; $pyr = $year - 1; } else { $pm = $month -1; $pyr = $year; }
if ($month == 12) { $nm = 1; $nyr = $year + 1; } else { $nm = $month + 1; $nyr = $year; }
?>

<div class="container">
    <a class="btn btn-primary" href="/dashboard/controllers/roster"><i class="fa fa-arrow-left"></i> Back</a>
    <br><br>
    <div class="row">
        <div class="col-sm-2">
            <a class="btn btn-primary" href="/dashboard/admin/roster/purge-assistant/<?=$pyr?>/<?=$pm?>"><i class="fa fa-arrow-left"></i> Previous Month</a></li>
        </div>
        <div class="col-sm-8">
            <center><h4><?=$mname?> 20<?=$year?></h4></center>
        </div>
        <div class="col-sm-2" align="right">
            <a class="btn btn-primary" href="/dashboard/admin/roster/purge-assistant/<?=$nyr?>/<?=$nm?>">Next Month <i class="fa fa-arrow-right"></i></a>
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
        <li class="nav-item">
            <a class="nav-link tab-link" href="#train" role="tab" data-toggle="tab">Mentors/Instructors</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name (CID)</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Hours This Month</th>
                        <th scope="col">Last Training Session</th>
                        <th scope="col">Last Activity Date</th>
                        <th scope="col">Join Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($homec as $c)
                        <tr>
                            <td><a href="/dashboard/admin/roster/edit/{{ $c->id }}">{{ $c->backwards_name }} ({{ $c->id }})</a></td>
                            <td>{{ $c->rating_short }}</td>
                            @if($stats[$c->id]->total_hrs >= 1)
                                <td class="black purge-green"><b>
                                    @if($last_stats[$c->id]->total_hrs <= 1)
                                        **{{ $stats[$c->id]->total_hrs }}
                                    @else
                                        {{ $stats[$c->id]->total_hrs }}
                                    @endif
                                </b></td>
                            @else
                                <td class="black purge-red"><b>
                                    @if($last_stats[$c->id]->total_hrs <= 1)
                                        **{{ $stats[$c->id]->total_hrs }}
                                    @else
                                        {{ $stats[$c->id]->total_hrs }}
                                    @endif
                                </b></td>
                            @endif
                            <td>
                                @if($c->last_training)
                                    <p>{{ $c->last_training }}</p>
                                @else
                                    <p><i>No Recent Training</i></p>
                                @endif
                            </td>
                            <td>{{ $c->last_logon }}</td>
                            <td>{{ $c->text_date_join }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="visit">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name (CID)</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Hours This Month</th>
                        <th scope="col">Last Activity Date</th>
                        <th scope="col">Join Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visitc as $c)
                        <tr>
                            <td><a href="/dashboard/admin/roster/edit/{{ $c->id }}">{{ $c->backwards_name }} ({{ $c->id }})</a></td>
                            <td>{{ $c->rating_short }}</td>
                            @if($stats[$c->id]->total_hrs >= 2)
                                <td class="black hours-success"><b>
                                    @if($last_stats[$c->id]->total_hrs <= 1)
                                        **{{ $stats[$c->id]->total_hrs }}
                                    @else
                                        {{ $stats[$c->id]->total_hrs }}
                                    @endif
                                </b></td>
                            @else
                                <td class="black hours-danger"><b>
                                    @if($last_stats[$c->id]->total_hrs <= 1)
                                        **{{ $stats[$c->id]->total_hrs }}
                                    @else
                                        {{ $stats[$c->id]->total_hrs }}
                                    @endif
                                </b></td>
                            @endif
                            <td>{{ $c->last_logon }}</td>
                            <td>{{ $c->text_date_create }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="train">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name (CID)</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Last Training Session</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trainc as $c)
                        <tr>
                            <td>
                                @if($c->hasRole('mtr'))
                                    <span class="badge badge-info">MTR</span>
                                @elseif($c->hasRole('ins'))
                                    <span class="badge badge-info">INS</span>
                                @endif
                                {{ $c->backwards_name }} ({{ $c->id }})
                            </td>
                            <td>{{ $c->rating_short }}</td>
                            <td>
                                @if($c->last_training_given)
                                    <p>{{ $c->last_training_given }}</p>
                                @else
                                    <p><i>No Recent Training Given</i></p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <p><i>**Controller did not control for at least 60 minutes in the previous month.</i></p>
</div>
<script src="{{asset('js/roster.js')}}">
@endsection
