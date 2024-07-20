@extends('layouts.dashboard')

@section('title')
Event Denylist
@endsection

@section('content')
@include('inc.header', ['title' => 'Event Denylist'])

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Vatsim ID</th>
            <th>Denylisted at</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($event_denylists as $event_denylist)
            <tr>
                <td>{{ $event_denylist->vatsim_id }}</td>
                <td>{{ $event_denylist->created_at }}</td>
                <td><a href="/dashboard/admin/events/denylist/delete/{{ $event_denylist->id }}" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete"><i class="fas fa-times fa-fw"></i></a></td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
