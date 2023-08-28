@extends('layouts.dashboard')

@section('title')
Training Tickets
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Training Tickets</h2>
    &nbsp;
</div>
<br>
<div class="container">
    @if($search_result != null)
        <a class="btn btn-primary" href="/dashboard/training/tickets/new?id={{ $search_result->id }}">Submit New Training Ticket</a>
    @else
        <a class="btn btn-primary" href="/dashboard/training/tickets/new">Submit New Training Ticket</a>
    @endif
    <br><br>
    <h5>Search Training Tickets:</h5>
    {!! Form::open(['url' => '/dashboard/training/tickets/search']) !!}
    <div class="row">
        <div class="col-sm-3">
            {!! Form::text('cid', null, ['placeholder' => 'Search by CID', 'class' => 'form-control']) !!}
        </div>
        <div class="col-sm-1">
            <button class="btn btn-primary" action="submit">Search</button>
        </div>
        <div class="col-sm-1">
            {!! Form::close() !!}
            <center>OR</center>
            {!! Form::open(['url' => '/dashboard/training/tickets/search']) !!}
        </div>
        <div class="col-sm-3">
            {!! Form::select('cid', $controllers, null, ['placeholder' => 'Select Controller', 'class' => 'form-control']) !!}
        </div>
        <div class="col-sm-1">
            <button class="btn btn-primary" action="submit">Search</button>
        </div>
    </div>
    {!! Form::close() !!}

    @if($search_result != null)
        <hr>
        <h5>Showing Training Data for {{ $search_result->full_name }} ({{ $search_result->id }})</h5>
            <div class="row">
                <div class="col-sm-4">VATUSA Academy Exam Scores:</div>
                @php ($examTypes = array('BASIC', 'S2', 'S3', 'C1'))
                    @foreach ($examTypes as $examType)
                        <div class="col-sm-2 text-white">
                            @isset($exams[$examType])
                                @if($exams[$examType]['success'] == 1)
                                    <span class="badge bg-success"><strong>{{ $examType }}:</strong> {{ $exams[$examType]['date'] }} ({{ $exams[$examType]['grade'] }}%)</span>
                                @elseif($exams[$examType]['success'] == 0)
                                    <span class="badge bg-danger"><strong>{{ $examType }}:</strong> {{ $exams[$examType]['date'] }} ({{ $exams[$examType]['grade'] }}%)</span>
                                @else
                                    <span class="badge bg-secondary"><strong>{{ $examType }}:</strong> No date</span>
                                @endif
                            @endisset
                        </div>
                    @endforeach
            </div>
        <hr>
        @php ($trainingCategories = array('s1', 's2', 's3', 'c1', 'other'))
        <ul class="nav nav-tabs nav-justified" role="tablist">
            @foreach($trainingCategories as $trainingCategory)
                @php ($active = '')
                @if ($loop->first)
                    @php ($active = ' active')
               @endif
                <li class="nav-item">
                    <a class="nav-link{{ $active }}" href="#{{ $trainingCategory }}" role="tab" data-toggle="tab" style="color:black">{{ ucfirst($trainingCategory) }}</a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content">
            @php($transition_date = \Carbon\Carbon::parse('11/12/2021')) {{-- ticket dates eastern timezone after this date --}}
            @foreach($trainingCategories as $trainingCategory)
                @php ($active = '')
                @if ($loop->first)
                    @php ($active = ' active')
               @endif
                <div role="tabpanel" class="tab-pane{{ $active }}" id="{{ $trainingCategory }}">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Training Date</th>
                                <th scope="col">Trainer Name</th>
                                <th scope="col">Position</th>
                                <th scope="col">Session Type</th>
                                <th scope="col">Session ID</th>
                                <th scope="col">Start Time</th>
                                <th scope="col">End Time</th>
                                <th scope="col">INS/MTR Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($tickets->count() > 0)
                            @foreach($tickets as $t)
                                @if($t->sort_category == $trainingCategory)
                                    @if($t->cert) {{-- student certified: green highlight --}}
                                        <tr class="table-success">
                                    @elseif($t->monitor) {{-- student may be monitored: blue highlight --}}
                                        <tr class="table-primary">
                                    @else
                                        <tr>
                                    @endif
                                    <td><a href="/dashboard/training/tickets/view/{{ $t->id }}">{{ $t->date }}</a></td>
                                    <td>{{ $t->trainer_name }}</td>
                                    <td>{{ $t->position_name }}</td>
                                    <td>{{ $t->type_name }}</td>
                                    <td>{{ $t->session_name }}</td>
                                    <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                                    <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                                    <td data-toggle="tooltip" title="{{ $t->ins_comments }}">{{ str_limit($t->ins_comments, 40, '...') }}</td>
                                </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">No training tickets found.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    {!! $tickets->appends(['id' => $search_result->id])->render() !!}
    @endif
</div>

@endsection