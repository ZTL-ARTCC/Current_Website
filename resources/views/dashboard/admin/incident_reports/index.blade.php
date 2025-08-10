@extends('layouts.dashboard')

@section('title')
Incident Report Management
@endsection

@section('content')
@include('inc.header', ['title' => 'Incident Report Management'])

<div class="container">
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#new" role="tab" data-bs-toggle="tab" style="color:black">New Reports</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#archive" role="tab" data-bs-toggle="tab" style="color:black">Archived Reports</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="new">
            @if(count($new_reports) > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Controller</th>
                            <th scope="col">Reporter</th>
                            <th scope="col">Time of Incident</th>
                            <th scope="col">Date of Incident</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($new_reports as $r)
                            <tr>
                                <td>{{ $r->controller_name }}</td>
                                <td>{{ $r->reporter_name }}</td>
                                <td>{{ $r->time }}</td>
                                <td>{{ $r->date }}</td>
                                <td>
                                    <a class="btn btn-success simple-tooltip" href="/dashboard/admin/incident/archive/{{ $r->id }}" data-bs-toggle="tooltip" title="Archive Report"><i class="fas fa-check"></i></a>
                                    <a class="btn btn-danger simple-tooltip" href="/dashboard/admin/incident/delete/{{ $r->id }}" data-bs-toggle="tooltip" title="Delete Report"><i class="fas fa-times"></i></a>
                                    <a class="btn btn-warning simple-tooltip" href="/dashboard/admin/incident/view/{{ $r->id }}" data-bs-toggle="tooltip" title="View Report"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                @include('inc.empty_state', ['header' => 'No New Incident Reports', 'body' => 'There are no new incident reports to show', 'icon' => 'fa-solid fa-person-falling'])
            @endif
        </div>

        <div role="tabpanel" class="tab-pane" id="archive">
            @if(count($archive_reports) > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Time</th>
                            <th scope="col">Date</th>
                            <th scope="col">Description</th>
                            <th scope="col">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archive_reports as $r)
                            <tr>
                                <td>{{ $r->time }}</td>
                                <td>{{ $r->date }}</td>
                                <td>{{ str_limit($r->description, 50, '...') }}</td>
                                <td>
                                    <a class="btn btn-warning simple-tooltip" href="/dashboard/admin/incident/view/{{ $r->id }}" data-bs-toggle="tooltip" title="View Report"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $archive_reports->links() !!}
            @else
                @include('inc.empty_state', ['header' => 'No Archived Incident Reports', 'body' => 'There are no archived incident reports to show', 'icon' => 'fa-solid fa-person-falling'])
            @endif
        </div>
    </div>
</div>
@endsection
