@extends('layouts.dashboard')

@section('title')
Events
@endsection

@section('content')
@include('inc.header', ['title' => 'Events'])

<div class="container">
    @if(Auth::user()->isAbleTo('events'))
        <a href="/dashboard/admin/events/new" class="btn btn-primary">New Event</a>
    @endif


    @if(Auth::user()->ability(['events-team'], ['staff', 'events']))
         @toggle('realops')
            <a href="/dashboard/admin/realops" class="btn btn-primary">Realops Admin</a>
         @endtoggle
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
                    @if($e->type == App\Event::$TYPES["UNVERIFIED_SUPPORT"] && Auth::user()->isAbleTo('events'))
                        <tr class="alert-warning">
                    @elseif($e->type == App\EVENT::$TYPES["VERIFIED_SUPPORT"] && Auth::user()->isAbleTo('events'))
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
                                    @if($e->type == App\Event::$TYPES["UNVERIFIED_SUPPORT"])
                                        <button disabled class="btn btn-success" title="This event has not been verified and cannot be made public"><i class="fas fa-check"></i></button>
                                    @else
                                        @if($e->status == App\Event::$STATUSES["HIDDEN"])
                                            <a href="/dashboard/admin/events/set-active/{{ $e->id }}" class="btn btn-success" data-toggle="tooltip" title="Unhide Event"><i class="fas fa-check fa-fw"></i></a>
                                        @elseif($e->status == App\Event::$STATUSES["VISIBLE"])
                                            <a href="/dashboard/admin/events/hide/{{ $e->id }}" class="btn btn-warning" data-toggle="tooltip" title="Hide Event"><i class="fas fa-ban fa-fw"></i></a>
                                        @endif
                                    @endif
                                        <a href="/dashboard/admin/events/edit/{{ $e->id }}" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt fa-fw"></i></a>
                                        @if($e->vatsim_id)
                                            <a href="/dashboard/admin/events/delete/{{ $e->id }}" class="btn btn-danger simple-tooltip" rel="tooltip" data-toggle="modal" data-target="#denylistEvent" title="Delete"><i class="fas fa-times fa-fw"></i></a>
                                        @else
                                            <a href="/dashboard/admin/events/delete/{{ $e->id }}" class="btn btn-danger simple-tooltip" toggle="tooltip" title="Delete"><i class="fas fa-times fa-fw"></i></a>
                                        @endif
                                </div>
                                    @if($e->type == App\Event::$TYPES["UNVERIFIED_SUPPORT"])
                                        <p><small><i>Unverified</i></small></p>
                                    @else
                                        @if($e->status == App\Event::$STATUSES["HIDDEN"])
                                            <br>
                                            <p><small><i>Hidden</i></small></p>
                                        @endif
                                    @endif
                            </td>
                        @endif
                    </tr>
                    <div class="modal fade" id="denylistEvent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Denylist Event</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Would you like to denylist this event?</p>
                                    <p>This will prevent this event from being reinstated on the next update command.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a href="/dashboard/admin/events/delete/{{ $e->id }}" class="btn btn-danger">Delete</a>
                                    <a href="/dashboard/admin/events/delete/{{ $e->id }}?denylist=true" class="btn btn-danger">Delete and Denylist Event</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <tr>
                    <td colspan="4">No events found.</td>
                </tr>
            @endif
        </tbody>
    </table>
    @if(method_exists($events,'links'))
       {!! $events->links() !!}
    @endif
</div>
@endsection
