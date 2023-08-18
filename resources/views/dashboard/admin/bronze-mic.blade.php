
@extends('layouts.dashboard')


@section('title')
Award Management
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Award Management</h2>
    &nbsp;
</div>
<br>

<?php
$mname = date("F", mktime(0, 0, 0, $month, 1, $year));
if ($month == 1) { $pm = 12; $pyr = $year - 1; } else { $pm = $month -1; $pyr = $year; }
if ($month == 12) { $nm = 1; $nyr = $year + 1; } else { $nm = $month + 1; $nyr = $year; }
?>

<div class="container">
    @if($month == 1)
        <center><a class="btn btn-warning" href="/dashboard/admin/pyrite-mic/<?=$pyr?>">View Pyrite Mic</a></center>
        <br>
    @endif
    <div class="row">
        <div class="col-sm-2">
            <a class="btn btn-primary" href="/dashboard/admin/bronze-mic/<?=$pyr?>/<?=$pm?>"><i class="fa fa-arrow-left"></i> Previous Month</a></li>
        </div>
        <div class="col-sm-8">
            <center><h4>Showing Eligible Hours for <?=$mname?> 20<?=$year?></h4></center>
        </div>
        <div class="col-sm-2" align="right">
            <a class="btn btn-primary" href="/dashboard/admin/bronze-mic/<?=$nyr?>/<?=$nm?>">Next Month <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">CID</th>
                <th scope="col">Rating</th>
                <th scope="col">Eligible Local Hero Monthly Hours to Date</th>
                <th scope="col">Eligible Bronze Monthly Hours to Date</th>
                <th scope="col">Eligible Pyrite Yearly Hours to Date</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($home as $h)
                <tr>
                    <td>{{ $h->full_name }}</td>
                    <td>{{ $h->id }}</td>
                    <td>{{ $h->rating_short }}</td>
                    <td>{{ $stats[$h->id]->local_hero_hrs }}</td>
                    <td>{{ $stats[$h->id]->bronze_hrs }}</td>
                    <td>{{ $year_stats[$h->id]->bronze_hrs }}</td>
                    <td>
                        <div class="btn-group">
                            @toggle('local-hero')
                                @if($winner_local == null)
                                    {!! Form::open(['url' => '/dashboard/admin/local-hero/'.$year.'/'.$month.'/'.$stats[$h->id]->local_hero_hrs.'/'.$h->id]) !!}
                                        @csrf
                                        <button action="submit" class="btn btn-primary btn-simple-tooltip" data-toggle="tooltip" title="Set as local hero Winner for <?=$mname?>"><i class="fas fa-trophy"></i></button>
                                    {!! Form::close() !!}
                                @elseif($winner_local->controller_id == $h->id)
                                    <a href="/dashboard/admin/local-hero/remove/{{ $winner_local->id }}/{{ $year }}/{{ $month }}" class="btn btn-secondary btn-simple-tooltip margin-right-xs" data-toggle="tooltip" title="Remove local hero Winner"><i class="fas fa-trophy"></i></a>
                                @else
                                    <p>Winner already selected.</p>
                                @endif
                            @endtoggle
                            @if($winner == null)
                                {!! Form::open(['url' => '/dashboard/admin/bronze-mic/'.$year.'/'.$month.'/'.$stats[$h->id]->bronze_hrs.'/'.$h->id]) !!}
                                    @csrf
                                    <button action="submit" class="btn btn-simple-tooltip" style="background-color:#C9AE5D" data-toggle="tooltip" title="Set as bronze Winner for <?=$mname?>"><i class="fa fa-microphone"></i></button>
                                {!! Form::close() !!}
                            @elseif($winner->controller_id == $h->id)
                                <a href="/dashboard/admin/bronze-mic/remove/{{ $winner->id }}/{{ $year }}/{{ $month }}" class="btn btn-secondary btn-simple-tooltip" data-toggle="tooltip" title="Remove bronze mic Winner"><i class="fa fa-microphone"></i></a>
                            @else
                                <p>Winner already selected.</p>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
