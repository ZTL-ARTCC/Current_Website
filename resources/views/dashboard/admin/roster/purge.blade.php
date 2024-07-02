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
        <div class="col-sm-8 text-center">
            <h4><?=$mname?> 20<?=$year?></h4>
        </div>
        <div class="col-sm-2 text-end">
            <a class="btn btn-primary" href="/dashboard/admin/roster/purge-assistant/<?=$nyr?>/<?=$nm?>">Next Month <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
    <br>
    @php ($statCategories = array('home', 'visiting'))
    <ul class="nav nav-tabs nav-justified" role="tablist">
        @foreach($statCategories as $statCategory)
            @php ($active = '')
            @if ($loop->first)
                @php ($active = ' active')
            @endif
            <li class="nav-item">
                <a class="nav-link{{ $active }}" href="#{{ $statCategory }}" role="tab" data-toggle="tab" style="color:black">{{ ucfirst($statCategory) }} Controllers</a>
            </li>
        @endforeach
        <li class="nav-item">
            <a class="nav-link tab-link" href="#train" role="tab" data-toggle="tab">Mentors/Instructors</a>
        </li>
    </ul>
    <div class="tab-content">
        @foreach($statCategories as $statCategory)
            @php ($active = '')
            @if ($loop->first)
                @php ($active = ' active')
            @endif
            <div role="tabpanel" class="tab-pane{{ $active }}" id="{{ $statCategory }}">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name (CID)</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Hours This Quarter</th>
                        @if($statCategory == 'home')
                            <th scope="col">Last Training Session</th>
                        @endif
                        <th scope="col">Last Activity Date</th>
                        <th scope="col">Join Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($$statCategory as $c)
                        <tr>
                            <td><a href="/dashboard/admin/roster/edit/{{ $c->id }}">{{ ucwords($c->backwards_name) }} ({{ $c->id }})</a></td>
                            <td>{{ $c->rating_short }}</td>
                            @if($stats[$c->id]->total_hrs >= 3)
                                <td class="black hours-success"><b>
                                    @if($last_stats[$c->id]->total_hrs <= 3)
                                        **{{ $stats[$c->id]->total_hrs }}
                                    @else
                                        {{ $stats[$c->id]->total_hrs }}
                                    @endif
                                </b></td>
                            @else
                                <td class="black hours-danger"><b>
                                    @if($last_stats[$c->id]->total_hrs <= 3)
                                        **{{ $stats[$c->id]->total_hrs }}
                                    @else
                                        {{ $stats[$c->id]->total_hrs }}
                                    @endif
                                </b></td>
                            @endif
                            @if($statCategory == 'home')
                            <td>
                                @if($c->last_training)
                                    <p>{{ $c->last_training }}</p>
                                @else
                                    <p><i>No Recent Training</i></p>
                                @endif
                            </td>
                            @endif
                            <td>{{ $c->last_logon }}</td>
                            <td>{{ $c->text_date_join }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
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
                                {{ ucwords($c->backwards_name) }} ({{ $c->id }})
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
    <p><i>**Controller did not meet 3 hours activity requirement during the previous quarter.</i></p>
</div>
<script src="{{asset('js/roster.js')}}"></script>
@endsection
