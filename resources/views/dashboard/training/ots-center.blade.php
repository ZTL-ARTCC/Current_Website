@extends('layouts.dashboard')

@section('title')
OTS Center
@endsection

@section('content')
@include('inc.header', ['title' => 'OTS Center'])

<div class="container">
    <h5>OTS Exam Recommendations</h5>
    <br>
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#new" role="tab" data-bs-toggle="tab" style="color:black">New Recommendations</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#accepted" role="tab" data-bs-toggle="tab" style="color:black">Accepted Recommendations</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#complete" role="tab" data-bs-toggle="tab" style="color:black">Completed OTS Exams</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="new">
            @if($ots_new->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Date of Recommendation</th>
                            <th scope="col">Controller Name</th>
                            <th scope="col">Recommender Name</th>
                            <th scope="col">Position</th>
                            <th scope="col">Actions</th>
                        </tr>
                    @foreach($ots_new as $o)
                        <tr>
                            <td>{{ $o->recommended_on }}</td>
                            <td>{{ $o->controller_name }}</td>
                            <td>{{ $o->recommender_name }}</td>
                            <td>{{ $o->position_name }}</td>
                            <td>
                                <a href="/dashboard/training/ots-center/accept/{{ $o->id }}" class="btn btn-success simple-tooltip" data-bs-toggle="tooltip" title="Accept Recommendation"><i class="fas fa-check"></i></a>
                                <a href="/dashboard/training/tickets?id={{ $o->controller_id }}" class="btn btn-info simple-tooltip" data-bs-toggle="tooltip" title="View Training Tickets."><i class="fas fa-check"></i></a>
                                @if(Auth::user()->isAbleTo('snrStaff'))
                                    <a href="/dashboard/training/ots-center/reject/{{ $o->id }}" class="btn btn-danger simple-tooltip" data-bs-toggle="tooltip" title="Reject Recommendation"><i class="fas fa-times"></i></a>
                                    <span data-bs-toggle="modal" data-bs-target="#assign{{ $o->id }}">
                                        <button type="button" class="btn btn-success simple-tooltip" data-bs-placement="top" data-bs-toggle="tooltip" title="Assign to Instructor"><i class="fas fa-user-check"></i></button>
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <div class="modal fade" id="assign{{ $o->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Assign OTS for {{ $o->controller_name }} to Instructor</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    {{ html()->form()->route('assignRecommendation', [$o->id])->open() }}
                                    @csrf
                                    <div class="modal-body">
                                    {{ html()->select('ins', $instructors, null)->placeholder('Select Instructor')->class(['form-control']) }}
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button action="submit" class="btn btn-success">Assign</button>
                                    </div>
                                    {{ html()->form()->close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </thead>
            </table>
            @else
                @include('inc.empty_state', ['header' => 'No New Recommendations', 'body' => 'No new OTS recommendations at this time', 'icon' => 'fa-solid fa-file'])
            @endif
        </div>
        <div role="tabpanel" class="tab-pane" id="accepted">
            @if($ots_accepted->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Date of Recommendation</th>
                            <th scope="col">Controller Name</th>
                            <th scope="col">Recommender Name</th>
                            <th scope="col">Instructor</th>
                            <th scope="col">Position</th>
                            <th scope="col">Actions</th>
                        </tr>
                    @foreach($ots_accepted as $o)
                        <tr>
                            <td>{{ $o->recommended_on }}</td>
                            <td>{{ $o->controller_name }}</td>
                            <td>{{ $o->recommender_name }}</td>
                            <td>{{ $o->ins_name }}</td>
                            <td>{{ $o->position_name }}</td>
                            <td>
                                @if($o->ins_id == Auth::id() || Auth::user()->isAbleTo('snrStaff'))
                                    <span bs-toggle="modal" data-bs-target="#completeOTS{{ $o->id }}">
                                        <button type="button" class="btn btn-success simple-tooltip" data-bs-placement="top" data-bs-toggle="tooltip" title="Set OTS as Complete"><i class="fas fa-check"></i></button>
                                    </span>
                                    <a href="/dashboard/training/ots-center/cancel/{{ $o->id }}" class="btn btn-warning simple-tooltip" data-bs-toggle="tooltip" title="Cancel OTS"><i class="fas fa-ban"></i></a>
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="completeOTS{{ $o->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Upload OTS Report</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    {{ html()->form()->route('completeOTS', [$o->id])->acceptsFiles()->open() }}
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="result">Result</label>
                                            {{ html()->select('result', [2 => 'Pass', 3 => 'Fail'], null)->placeholder('Select Result')->class(['form-control']) }}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button action="submit" class="btn btn-success">Set Complete</button>
                                    </div>
                                    {{ html()->form()->close() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </thead>
            </table>
            @else
                @include('inc.empty_state', ['header' => 'No Accepted Recommendations', 'body' => 'No accepted OTS recommendations at this time', 'icon' => 'fa-solid fa-file'])
            @endif
        </div>
        <div role="tabpanel" class="tab-pane" id="complete">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Date of Recommendation</th>
                        <th scope="col">Controller Name</th>
                        <th scope="col">Recommender Name</th>
                        <th scope="col">Instructor Name</th>
                        <th scope="col">Position</th>
                        <th scope="col">Result</th>
                    </tr>
                    @if($ots_complete->count() > 0)
                        @foreach($ots_complete as $o)
                            <tr>
                                <td>{{ $o->recommended_on }}</td>
                                <td>{{ $o->controller_name }}</td>
                                <td>{{ $o->recommender_name }}</td>
                                <td>{{ $o->ins_name }}</td>
                                <td>{{ $o->position_name }}</td>
                                <td>{{ $o->result }}@if($o->result == 'Pass') <i class="fas fa-check"></i>@else <i class="fas fa-times"></i>@endif</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No completed OTS exams found.</td>
                        </tr>
                    @endif
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection
