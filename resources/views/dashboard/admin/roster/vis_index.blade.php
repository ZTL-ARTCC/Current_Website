@extends('layouts.dashboard')

@section('title')
Visit Requests
@endsection

@section('content')
<div class="container-fluid view-header">
    <h2>Visit Requests</h2>
</div>
<br>
<div class="container">
    <div class="form-group inline">
        <a href="/dashboard/controllers/roster" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back</a>
        <span data-toggle="modal" data-target="#manualAdd">
            <button type="button" class="btn btn-warning">Manual Add Controller</button>
        </span>
    </div>
    <br><br>
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link tab-link active" href="#new" role="tab" data-toggle="tab">New Requests</a>
        </li>
        <li class="nav-item">
            <a class="nav-link tab-link" href="#accepted" role="tab" data-toggle="tab">Accepted Requests</a>
        </li>
        <li class="nav-item">
            <a class="nav-link tab-link" href="#rejected" role="tab" data-toggle="tab">Rejected Requests</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="new">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name (CID)</th>
                        <th scope="col" class="text-center">Rating</th>
                        <th scope="col" class="text-center">Email</th>
                        <th scope="col" class="text-center">Home ARTCC/Division</th>
                        <th scope="col" class="text-center">Submitted at</th>
                        <th scope="col" class="text-center" width="120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($new)
                        @foreach($new as $v)
                            <tr>
                                <td data-toggle="tooltip" title="{{ $v->reason }}">{{ $v->name }} ({{ $v->cid }})</td>
                                <td class="text-center">{{ $v->rating_short }}</td>
                                <td class="text-center">{{ $v->email }}</td>
                                <td class="text-center">{{ $v->home }}</td>
                                <td class="text-center">{{ $v->updated_at }}</td>
                                <td>
                                    <a href="/dashboard/admin/roster/visit/accept/{{ $v->id }}" class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Accept"><i class="fa fa-check"></i></a>
                                    <span data-toggle="modal" data-target="#reject{{ $v->id }}">
                                        <button type="button" class="btn btn-danger simple-tooltip" data-placement="top" data-toggle="tooltip" title="Reject"><i class="fas fa-times"></i></button>
                                    </span>
                                </td>
                            </tr>
                            <div class="modal fade" id="reject{{ $v->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reason for Visit Request Rejection</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        {!! Form::open(['action' => ['AdminDash@rejectVisitRequest', $v->id]]) !!}
                                        @csrf
                                        <div class="modal-body">
                                            {!! Form::label('reject_reason', 'Please specify why the visit request is being rejected. This will be sent to the requesting visitor with a rejection notification.') !!}
                                            {!! Form::textArea('reject_reason', 'Your visit request has been rejected.', ['placeholder' => 'Required', 'class' => 'form-control']) !!}
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button action="submit" class="btn btn-success">Confirm</button>
                                            </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p>No new visit requests to show.</p>
                    @endif
                </tbody>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="accepted">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name (CID)</th>
                        <th class="text-center" scope="col">Rating</th>
                        <th class="text-center" scope="col">Email</th>
                        <th class="text-center" scope="col">Home ARTCC/Division</th>
                        <th class="text-center" scope="col">Accepted at</th>
                        <th class="text-center" scope="col">Accepted by</th>
                    </tr>
                </thead>
                <tbody>
                    @if($accepted)
                        @foreach($accepted as $v)
                            <tr>
                                <td class="text-center" data-toggle="tooltip" title="{{ $v->reason }}">{{ $v->name }} ({{ $v->cid }})</td>
                                <td class="text-center">{{ $v->rating_short }}</td>
                                <td class="text-center">{{ $v->email }}</td>
                                <td class="text-center">{{ $v->home }}</td>
                                <td class="text-center">{{ $v->updated_at }}</td>
                                <td class="text-center">{{ App\User::find($v->updated_by)->full_name }}</td>
                            </tr>
                        @endforeach
                    @else
                        <p>No accepted visit requests to show.</p>
                    @endif
                </tbody>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="rejected">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name (CID)</th>
                        <th class="text-center" scope="col">Rating</th>
                        <th class="text-center" scope="col">Email</th>
                        <th class="text-center" scope="col">Home ARTCC/Division</th>
                        <th class="text-center" scope="col">Rejected at</th>
                        <th class="text-center" scope="col">Rejected by</th>
                    </tr>
                </thead>
                <tbody>
                    @if($rejected)
                        @foreach($rejected as $v)
                            <tr>
                                <td class="text-center" data-toggle="tooltip" title="{{ $v->reason }}">{{ $v->name }} ({{ $v->cid }})</td>
                                <td class="text-center">{{ $v->rating_short }}</td>
                                <td class="text-center">{{ $v->email }}</td>
                                <td class="text-center">{{ $v->home }}</td>
                                <td class="text-center">{{ $v->updated_at }}</td>
                                <td class="text-center">{{ App\User::find($v->updated_by)->full_name }}</td>
                            </tr>
                        @endforeach
                    @else
                        <p>No rejected visit requests to show.</p>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="manualAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manually Add Controller</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['action' => 'AdminDash@manualAddVisitor']) !!}
                @csrf
                <div class="modal-body">
                    <div class="container">
                        <div class="form-group">
                            <div class="row">
                                {!! Form::label('cid', 'Controller CID') !!}
                                {!! Form::text('cid', null, ['placeholder' => 'Controller CID', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button action="submit" class="btn btn-success">Search CID</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
