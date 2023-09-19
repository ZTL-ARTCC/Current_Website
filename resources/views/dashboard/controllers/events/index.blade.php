@extends('layouts.dashboard')

@section('title')
Events
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Events</h2>
    &nbsp;
</div>
<br>

<div class="container">
    @if(Auth::user()->isAbleTo('events'))
        <a href="/dashboard/admin/events/new" class="btn btn-primary">New Event</a>
        <br><br>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Event</th>
                <th scope="col"><center>Date</center></th>
                <th scope="col"><center>Time</center></th>
                @if(Auth::user()->isAbleTo('events'))
                    <th scope="col"><center>Actions</center></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if($events->count() > 0)
                @foreach($events as $e)
                    @if($e->type == 2 && Auth::user()->isAbleTo('events'))
                        <tr class="alert-warning">
                    @elseif($e->type == 1 && Auth::user()->isAbleTo('events'))
                        <tr class="alert-info">
                    @else
                        <tr>
                    @endif
                        @if($e->banner_path != null)
                            <td width="500px"><a href="/dashboard/controllers/events/view/{{ $e->id }}"><img src="{{ $e->banner_path }}" width="500px" alt="{{ $e->name }}"></a></td>
                        @else
                            <td width="500px"><a href="/dashboard/controllers/events/view/{{ $e->id }}"><h4>{{ $e->name }}</h4></a></td>
                        @endif
                        <td>{{ $e->date }}</td>
                        <td>
                            {{ $e->start_time }} to {{ $e->end_time }} Zulu
                            ({{ timeToLocal($e->start_time, Auth::user()->timezone) }} to {{ timeToLocal($e->end_time, Auth::user()->timezone) }} <a style="color:inherit" href="#" data-toggle="tooltip"
                                                                                                                                                     title="Showing times in {{ Auth::user()->timezone }}. You can change this on your profile."><i
                                        class="fas fa-info-circle"></i></a>)
                        </td>
                        @if(Auth::user()->isAbleTo('events'))
                            <td>

                                <div class="btn-group" role="group" aria-label="Actions">
                                    @if($e->type == 2)
                                        <button disabled class="btn btn-success" title="This event has not been verified and cannot be made public"><i class="fas fa-check"></i></button>
                                    @else
                                        @if($e->status == 0)
                                            <a href="/dashboard/admin/events/set-active/{{ $e->id }}" class="btn btn-success" data-toggle="tooltip" title="Unhide Event"><i class="fas fa-check fa-fw"></i></a>
                                        @elseif($e->status == 1)
                                            <a href="/dashboard/admin/events/hide/{{ $e->id }}" class="btn btn-warning" data-toggle="tooltip" title="Hide Event"><i class="fas fa-ban fa-fw"></i></a>
                                        @endif
                                    @endif
                                        <a href="/dashboard/admin/events/edit/{{ $e->id }}" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt fa-fw"></i></a>
                                        <a href="/dashboard/admin/events/delete/{{ $e->id }}" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times fa-fw"></i></a>
                                </div>
                                    @if($e->type == 2)
                                        <p><small><i>Unverified</i></small></p>
                                    @else
                                        @if($e->status == 0)
                                            <br>
                                            <p><small><i>Hidden</i></small></p>
                                        @endif
                                    @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">No events found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
