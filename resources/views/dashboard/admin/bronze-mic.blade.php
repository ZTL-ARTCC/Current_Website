
@extends('layouts.dashboard')


@section('title')
Award Management
@endsection

@section('content')
@include('inc.header', ['title' => 'Award Management'])

<?php
$mname = date("F", mktime(0, 0, 0, $month, 1, $year));
if ($month == 1) { $pm = 12; $pyr = $year - 1; } else { $pm = $month -1; $pyr = $year; }
if ($month == 12) { $nm = 1; $nyr = $year + 1; } else { $nm = $month + 1; $nyr = $year; }
if (!in_array($sort, ['localsort', 'bronzesort', 'pyritesort'])) { $sort = 'bronzesort'; }
?>

<div class="container">
    @if($month == 1)
        <center><a class="btn btn-warning" href="/dashboard/admin/pyrite-mic/<?=$pyr?>">View Pyrite Mic</a></center>
        <br>
    @endif
    <div class="row">
        <div class="col-sm-2">
            <a class="btn btn-primary" href="/dashboard/admin/bronze-mic/<?=$sort?>/<?=$pyr?>/<?=$pm?>"><i class="fa fa-arrow-left"></i> Previous Month</a></li>
        </div>
        <div class="col-sm-8">
            <center><h4>Showing Eligible Hours for <?=$mname?> 20<?=$year?></h4></center>
        </div>
        <div class="col-sm-2" align="right">
            <a class="btn btn-primary" href="/dashboard/admin/bronze-mic/<?=$sort?>/<?=$nyr?>/<?=$nm?>">Next Month <i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <br>
            <span data-toggle="modal" data-target="#updateLocalHeroChallenge">
                <button type="button" class="btn btn-primary"><i class="fas fa-cog"></i> Configure Local Hero Challenge</a></button>
            </span>
        </div>
    </div>
    <br>
    <table class="table table-bordered table-striped">
        <colgroup>
            <col></col>
            <col></col>
            <col></col>
            @if($sort == 'localsort')
                <col class="bg-warning"></col>
            @else
                <col></col>
            @endif
            @if($sort == 'bronzesort')
                <col class="bg-warning"></col>
            @else
                <col></col>
            @endif
            @if($sort == 'pyritesort')
                <col class="bg-warning"></col>
            @else
                <col></col>
            @endif
            <col></col>
        </colgroup>
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">CID</th>
                <th scope="col">Rating</th>
                @if($sort == 'localsort')
                    <th scope="col">Eligible Local Hero Monthly Hours to Date</th>
                @else
                    <th scope="col">Eligible Local Hero Monthly Hours to Date <a class="btn btn-primary btn-sm" href="/dashboard/admin/bronze-mic/localsort/<?=$year?>/<?=$month?>" data-toggle="tooltip" title="Sort by local hero"><i class="fas fa-chevron-down"></i></a></th>
                @endif
                @if($sort == 'bronzesort')
                    <th scope="col">Eligible Bronze Monthly Hours to Date</th>
                @else
                    <th scope="col">Eligible Bronze Monthly Hours to Date <a class="btn btn-primary btn-sm" href="/dashboard/admin/bronze-mic/bronzesort/<?=$year?>/<?=$month?>" data-toggle="tooltip" title="Sort by bronze"><i class="fas fa-chevron-down"></i></a></th>
                @endif
                @if($sort == 'pyritesort')
                    <th scope="col">Eligible Pyrite Yearly Hours to Date</th>
                @else
                    <th scope="col">Eligible Pyrite Yearly Hours to Date <a class="btn btn-primary btn-sm"href="/dashboard/admin/bronze-mic/pyritesort/<?=$year?>/<?=$month?>" data-toggle="tooltip" title="Sort by pyrite"><i class="fas fa-chevron-down"></i></a></th>
                @endif
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
                    <td style="min-width:125px">
                        <div class="btn-toolbar">
                            @toggle('local-hero')
                                @if($winner_local == null)
                                    {{ html()->form('POST', '/dashboard/admin/local-hero/'.$year.'/'.$month.'/'.$stats[$h->id]->local_hero_hrs.'/'.$h->id) }}
                                        @csrf
                                        <button action="submit" class="btn btn-primary btn-simple-tooltip mr-2" data-toggle="tooltip" title="Set as local hero Winner for <?=$mname?>"><i class="fas fa-trophy"></i></button>
                                    {{ html()->form()->close() }}
                                @elseif($winner_local->controller_id == $h->id)
                                    <a href="/dashboard/admin/local-hero/remove/{{ $winner_local->id }}/{{ $year }}/{{ $month }}" class="btn btn-secondary btn-simple-tooltip mr-2" data-toggle="tooltip" title="Remove local hero Winner"><i class="fas fa-trophy"></i></a>
                                @endif
                            @endtoggle
                            @if($winner == null)
                                {{ html()->form('POST', '/dashboard/admin/bronze-mic/'.$year.'/'.$month.'/'.$stats[$h->id]->bronze_hrs.'/'.$h->id) }}
                                    @csrf
                                    <button action="submit" class="btn btn-simple-tooltip bronze-bg" data-toggle="tooltip" title="Set as bronze Winner for <?=$mname?>"><i class="fa fa-microphone"></i></button>
                                {{ html()->form()->close() }}
                            @elseif($winner->controller_id == $h->id)
                                <a href="/dashboard/admin/bronze-mic/remove/{{ $winner->id }}/{{ $year }}/{{ $month }}" class="btn btn-secondary btn-simple-tooltip" data-toggle="tooltip" title="Remove bronze mic Winner"><i class="fa fa-microphone"></i></a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="modal fade" id="updateLocalHeroChallenge" tabindex="-1" role="dialog" aria-labelledby="updateLocalHeroChallenge" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Local Hero Challenge Configuration for <?=$mname?> 20<?=$year?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                </div>
                {{ html()->form()->route('AdminDash@updateLocalHeroChallenge', [$challenge->id]) }}
                @csrf
                <div class="modal-body mx-2">
                    <div class="row mb-2">
                        {{ html()->label('Name of the monthly challenge', 'title') }}
                        {{ html()->text('title', $challenge->title)->class(['form-control']) }}
                    </div>
                    <div class="row mb-2">
                        {{ html()->label('Description of the monthly challenge', 'description') }}
                        {{ html()->textarea('description', $challenge->description)->class(['form-control'])->attributes(['rows' => '5']) }}
                    </div>
                    <div class="row form-check mb-2">
                        @toggle('local-hero')
                            {{ html()->checkbox('postToNews', true, 1)->class(['form-check-input']) }}
                        @else
                            {{ html()->checkbox('postToNews', false, 1)->class(['form-check-input'])->attributes(['disabled' => 'disabled']) }}
                        @endtoggle
                        {{ html()->label('Post challenge to site news feed?', 'postToNews')->class(['form-check-label']) }}
                    </div>
                    <hr>
                    <div class="row">
                        {{ html()->label('Control Positions for this Challenge', 'positions') }}
                        {{ html()->multiselect('positions', $local_hero_challenge_positions, $challenge->positions)->class(['form-control'])->attributes(['size' => '5']) }}
                        <div class="small text-secondary">Hold CTRL + click to select multiple positions</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button action="submit" class="btn btn-success">Save Challenge</button>
                    </div>
                    {{ html()->hidden('year', $year) }}
                    {{ html()->hidden('month', $month) }}
                {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
