@extends('layouts.dashboard')

@section('title')
Calendar/News
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Calendar/News</h2>
    &nbsp;
</div>
<br>
<div class="container">
    <a class="btn btn-primary" href="/dashboard/admin/calendar/new">New Calendar Event/News</a>
    <br><br>
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#calendar" role="tab" data-toggle="tab" style="color:black">Calendar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#news" role="tab" data-toggle="tab" style="color:black">News</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="calendar">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Date/Time</th>
                        <th scope="col"><center>Title</center></th>
                        <th scope="col"><center>Body</center></th>
                        <th scope="col"><center>Created By</center></th>
                        <th scope="col"><center>Last Updated By</center></th>
                        <th scope="col" style="width:110px"><center>Actions</center></th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($calendar) > 0)
                        @foreach($calendar as $c)
                            <tr>
                                @if($c->time == null)
                                    <td>{{ $c->date }}</td>
                                @else
                                    <td>{{ $c->date }} {{ $c->time }}</td>
                                @endif
                                <td><center><a href="/dashboard/admin/calendar/view/{{ $c->id }}">{{ $c->title }}</a></center></td>
                                <td><center>{!! str_limit($c->body, 50, '...') !!}</center></td>
                                <td data-toggle="tooltip" title="{{ $c->created_at }}"><center>{{ App\User::find($c->created_by)->full_name }}</center></td>
                                @if($c->updated_by != null)
                                    <td data-toggle="tooltip" title="{{ $c->updated_at }}"><center>{{ App\User::find($c->updated_by)->full_name }}</center></td>
                                @else
                                    <td></td>
                                @endif
                                <td>
                                    {!! Form::open(['action' => ['AdminDash@deleteCalendarEvent', $c->id]]) !!}
                                        @csrf
                                        {!! Form::hidden('_method', 'DELETE') !!}
                                        <a class="btn btn-success simple-tooltip" href="/dashboard/admin/calendar/edit/{{ $c->id }}" data-toggle="tooltip" title="Edit"><i class="far fa-edit"></i></a>
                                        <button class="btn btn-danger simple-tooltip" type="submit" data-toggle="tooltip" title="Delete"><i class="fa fa-times"></i></button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <p>No calendar events to show.</p>
                    @endif
                </tbody>
            </table>
        </div>

        <div role="tabpanel" class="tab-pane" id="news">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col"><center>Title</center></th>
                        <th scope="col"><center>Body</center></th>
                        <th scope="col"><center>Created By</center></th>
                        <th scope="col"><center>Last Updated By</center></th>
                        <th scope="col" style="width:110px"><center>Actions</center></th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($news) > 0)
                        @foreach($news as $c)
                            <tr>
                                @if($c->time == null)
                                    <td>{{ $c->date }}</td>
                                @else
                                    <td>{{ $c->date }} {{ $c->time }}</td>
                                @endif
                                <td><center><a href="/dashboard/admin/calendar/view/{{ $c->id }}">{{ $c->title }}</a></center></td>
                                <td><center>{!! str_limit($c->body, 50, '...') !!}</center></td>
                                <td data-toggle="tooltip" title="{{ $c->created_at }}"><center>{{ App\User::find($c->created_by)->full_name }}</center></td>
                                @if($c->updated_by != null)
                                    <td data-toggle="tooltip" title="{{ $c->updated_at }}"><center>{{ App\User::find($c->updated_by)->full_name }}</center></td>
                                @else
                                    <td></td>
                                @endif
                                <td>
                                    {!! Form::open(['action' => ['AdminDash@deleteCalendarEvent', $c->id]]) !!}
                                        @csrf
                                        {!! Form::hidden('_method', 'DELETE') !!}
                                        <a class="btn btn-success simple-tooltip" href="/dashboard/admin/calendar/edit/{{ $c->id }}" data-toggle="tooltip" title="Edit"><i class="far fa-edit"></i></a>
                                        <button class="btn btn-danger simple-tooltip" type="submit" data-toggle="tooltip" title="Delete"><i class="fa fa-times"></i></button>
                                    {!! Form::close() !!}
                                </td>

                                <td>
                                    {!! Form::open(['action' => ['AdminDash@toggleCalenderEventVisibilty', $c->id]]) !!}
                                    @csrf
                                    @if($c->visible == 0)
                                        <button class="btn btn-success" data-toggle="tooltip" title="Show News"><i class="far fa-eye"></i></button>
                                    @elseif($c->visible == 1)
                                        <button class="btn btn-warning" data-toggle="tooltip" title="Hide News"><i class="fas fa-ban"></i></button>
                                    @endif
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <p>No news to show.</p>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
