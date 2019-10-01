@extends('layouts.dashboard')

@section('title')
OTS Center
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>OTS Center</h2>
    &nbsp;
</div>
<br>

<div class="container">
    <h5>OTS Exam Recommendations</h5>
    <br>
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#new" role="tab" data-toggle="tab" style="color:black">New Recommendations</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#accepted" role="tab" data-toggle="tab" style="color:black">Accepted Recommendations</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#complete" role="tab" data-toggle="tab" style="color:black">Completed OTS Exams</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="new">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Date of Recommendation</th>
                        <th scope="col">Controller Name</th>
                        <th scope="col">Recommender Name</th>
                        <th scope="col">Position</th>
                        <th scope="col">Actions</th>
                    </tr>
                    @if($ots_new->count() > 0)
                        @foreach($ots_new as $o)
                            <tr>
                                <td>{{ $o->recommended_on }}</td>
                                <td>{{ $o->controller_name }}</td>
                                <td>{{ $o->recommender_name }}</td>
                                <td>{{ $o->position_name }}</td>
                                <td>
                                    <a href="/dashboard/training/ots-center/accept/{{ $o->id }}" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Accept Recommendation"><i class="fas fa-check"></i></a>
                                    <a href="/dashboard/training/tickets?id={{ $o->controller_id }}" class="btn btn-info simple-tooltip" data-toggle="tooltip" title="View Training Tickets."><i class="fas fa-check"></i></a>
                                    @if(Auth::user()->can('snrStaff'))
                                        <a href="/dashboard/training/ots-center/reject/{{ $o->id }}" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Reject Recommendation"><i class="fas fa-times"></i></a>
                                        <span data-toggle="modal" data-target="#assign{{ $o->id }}">
                                            <button type="button" class="btn btn-success simple-tooltip" data-placement="top" data-toggle="tooltip" title="Assign to Instructor"><i class="fas fa-user-check"></i></button>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <div class="modal fade" id="assign{{ $o->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Assign OTS for {{ $o->controller_name }} to Instructor</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        {!! Form::open(['action' => ['TrainingDash@assignRecommendation', $o->id]]) !!}
                                        @csrf
                                        <div class="modal-body">
                                        {!! Form::select('ins', $instructors, null, ['placeholder' => 'Select Instructor', 'class' => 'form-control']) !!}
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button action="submit" class="btn btn-success">Assign</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No new OTS exam recommendations found.</td>
                        </tr>
                    @endif
                </thead>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="accepted">
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
                    @if($ots_accepted->count() > 0)
                        @foreach($ots_accepted as $o)
                            <tr>
                                <td>{{ $o->recommended_on }}</td>
                                <td>{{ $o->controller_name }}</td>
                                <td>{{ $o->recommender_name }}</td>
                                <td>{{ $o->ins_name }}</td>
                                <td>{{ $o->position_name }}</td>
                                <td>
                                    @if($o->ins_id == Auth::id() || Auth::user()->can('snrStaff'))
                                        <span data-toggle="modal" data-target="#completeOTS{{ $o->id }}">
                                            <button type="button" class="btn btn-success simple-tooltip" data-placement="top" data-toggle="tooltip" title="Set OTS as Complete"><i class="fas fa-check"></i></button>
                                        </span>
                                        <a href="/dashboard/training/ots-center/cancel/{{ $o->id }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Cancel OTS"><i class="fas fa-ban"></i></a>
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="completeOTS{{ $o->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload OTS Report</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        {!! Form::open(['action' => ['TrainingDash@completeOTS', $o->id], 'files' => 'true']) !!}
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                {!! Form::label('result', 'Result') !!}
                                                {!! Form::select('result', [2 => 'Pass', 3 => 'Fail'], null, ['placeholder' => 'Select Result', 'class' => 'form-control']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::file('ots_report', ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button action="submit" class="btn btn-success">Set Complete</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No accepted OTS exam recommendations found.</td>
                        </tr>
                    @endif
                </thead>
            </table>
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
                                <td>{{ $o->result }}@if($o->result == 'Pass') <i class="fas fa-check"></i>@else <i class="fas fa-times"></i>@endif <a href="{{ $o->report }}" target="_blank">(View Report)</a></td>
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
