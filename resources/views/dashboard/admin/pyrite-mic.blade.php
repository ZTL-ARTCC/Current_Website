@extends('layouts.dashboard')

@section('title')
Pyrite Mic
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Pyrite Mic Management</h2>
    &nbsp;
</div>
<br>

<?php
$nyr = $year + 1;
?>

<div class="container">
    <center><a class="btn btn-warning" href="/dashboard/admin/bronze-mic/<?=$nyr?>/1"><i class="fa fa-arrow-left"></i> Back to Pyrite Mic</a></center>
    <br>
    <center><h4>Showing Eligible Hours for 20<?=$year?></h4></center>
    <br>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">CID</th>
                <th scope="col">Rating</th>
                <th scope="col">Eligible Yearly Hours to Date</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($home as $h)
                <tr>
                    <td>{{ $h->full_name }}</td>
                    <td>{{ $h->id }}</td>
                    <td>{{ $h->rating_short }}</td>
                    <td>{{ $year_stats[$h->id]->bronze_hrs }}</td>
                    <td>
                        @if($winner == null)
                            {!! Form::open(['url' => '/dashboard/admin/pyrite-mic/'.$year.'/'.$year_stats[$h->id]->bronze_hrs.'/'.$h->id]) !!}
                                @csrf
                                <button action="submit" class="btn btn-success btn-simple-tooltip" data-toggle="tooltip" title="Set as Winner for 20<?=$year?>"><i class="fas fa-check"></i></button>
                            {!! Form::close() !!}
                        @elseif($winner->controller_id == $h->id)
                            <a href="/dashboard/admin/pyrite-mic/remove/{{ $winner->id }}/{{ $year }}" class="btn btn-danger btn-simple-tooltip" data-toggle="tooltip" title="Remove Winner"><i class="fas fa-times"></i></a>
                        @else
                            <p>Winner already selected.</p>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
