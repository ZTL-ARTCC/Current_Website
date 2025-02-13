@extends('layouts.dashboard')

@section('title')
Training Tickets
@endsection

@section('content')
@include('inc.header', ['title' => 'Training Tickets'])

<div class="container">
    @if($search_result != null)
        <a class="btn btn-primary" href="/dashboard/training/tickets/new?id={{ $search_result->id }}">Submit New Training Ticket</a>
    @else
        <a class="btn btn-primary" href="/dashboard/training/tickets/new">Submit New Training Ticket</a>
    @endif
    <br><br>
    <h5>Search Training Tickets:</h5>
    {{ html()->form('POST', '/dashboard/training/tickets/search')->open() }}
    <div class="row">
        <div class="col-sm-3">
            {{ html()->text('cid', null)->placeholder('Search by CID')->class(['form-control']) }}
        </div>
        <div class="col-sm-1">
            <button class="btn btn-primary" action="submit">Search</button>
        </div>
        <div class="col-sm-1">
            {{ html()->form()->close() }}
            <center>OR</center>
            {{ html()->form('POST', '/dashboard/training/tickets/search')->open() }}
        </div>
        <div class="col-sm-3">
            {{ html()->select('cid', $controllers, null)->placeholder('Select Controller')->class(['form-control']) }}
        </div>
        <div class="col-sm-1">
            <button class="btn btn-primary" action="submit">Search</button>
        </div>
    </div>
    {{ html()->form()->close() }}

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
            <div class="row">
                <div class="col-sm-12">Date of Last Promotion: <strong>{{ $search_result->last_promotion_date }}</strong></div>
            </div>
        <hr>
        @php ($trainingCategories = array('drafts', 's1', 's2', 's3', 'c1', 'other'))
        <ul class="nav nav-tabs nav-justified" role="tablist">
            @foreach($trainingCategories as $trainingCategory)
                @if ($trainingCategory == 'drafts' && !$drafts)
                    @continue
                @endif
                @php ($active = '')
                @if ($loop->first || (!$drafts && $loop->iteration == 2))
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
                @if ($trainingCategory == 'drafts' && !$drafts)
                    @continue
                @endif
                @php ($active = '')
                @if ($loop->first || (!$drafts && $loop->iteration == 2))
                    @php ($active = ' active')
                @endif
                <div role="tabpanel" class="tab-pane{{ $active }}" id="{{ $trainingCategory }}">
                    @if($tickets->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">View</th>
                                    <th scope="col">Training Date</th>
                                    <th scope="col">Trainer Name</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Session Type</th>
                                    <th scope="col">Session ID</th>
                                    <th scope="col">Start Time</th>
                                    <th scope="col">End Time</th>
                                    <th scope="col">Score<br>(1-5)</th>
                                    <th scope="col">Movements</th>
                                    <th scope="col">INS/MTR Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($tickets as $t)
                                @if($t->sort_category == $trainingCategory)
                                    @if($t->cert) {{-- student certified: green highlight --}}
                                        <tr class="table-success">
                                    @elseif($t->monitor) {{-- student may be monitored: blue highlight --}}
                                        <tr class="table-primary">
                                    @else
                                        <tr>
                                    @endif
                                    <td>
                                        <a href="/dashboard/training/tickets/view/{{ $t->id }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>{{ $t->date }}</td>
                                    <td>{{ $t->trainer_name }}</td>
                                    <td>{{ $t->position_name }}</td>
                                    <td>{{ $t->type_name }}</td>
                                    <td>{{ $t->session_name }}</td>
                                    <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                                    <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                                    <td>@if($t->score) {{ $t->score }} @else N/A @endif</p>
	                                <td>@if($t->movements) {{ $t->movements }} @else N/A @endif</td>
                                    @if($t->controller_id == Auth::id() && Auth::user()->hasRole('mtr'))
                                        <td data-toggle="tooltip" title="Not Authorized">Not Authorzized</td>
                                    @else
                                        <td data-toggle="tooltip" title="{{ $t->ins_comments }}">{{ str_limit($t->ins_comments, 40, '...') }}</td>
                                    @endif
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        @include('inc.empty_state', ['header' => 'No Training Tickets', 'body' => 'There are no training tickets for this controller under this category', 'icon' => 'fa-solid fa-file'])
                    @endif
                </div>
            @endforeach
        </div>
    {!! $tickets->appends(['id' => $search_result->id])->render() !!}
    @elseif($all_drafts != null && count($all_drafts) != 0)
        <hr />
        <h4>All Open Drafts</h4>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">View</th>
                    <th scope="col">Trainer Name</th>
                    <th scope="col">Controller Name</th>
                    <th scope="col">Date Created</th>
                    <th scope="col">Date Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach($all_drafts as $ticket)
                    <tr>
                        <td>
                            <a href="/dashboard/training/tickets/view/{{ $ticket->id }}" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                        <td>{{ $ticket->trainer_name }}</td>
                        <td>{{ $ticket->controller_name }}</td>
                        <td>{{ date_format($ticket->created_at, 'm/d/Y') }}</td>
                        <td>{{ date_format($ticket->updated_at, 'm/d/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <br />
        @include('inc.empty_state', ['header' => 'No Open Drafts', 'body' => 'There are no open training ticket drafts to show here.', 'icon' => 'fa-solid fa-file'])
    @endif
</div>
@endsection
