@extends('layouts.dashboard')

@section('title')
    Visit Requests
@endsection

@section('content')
    @include('inc.header', ['title' => 'Visit Requests'])
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
                @if($new->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th scope="col" class="text-left">Name (CID)</th>
                                <th scope="col">Rating</th>
                                <th scope="col">Email</th>
                                <th scope="col">Home ARTCC/Division</th>
                                <th scope="col">Submitted at</th>
                                <th scope="col" width="120px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($new as $v)
                                <tr class="text-center">
                                    <td class="text-left" data-toggle="tooltip" title="{{ $v->reason }}">{{ $v->name }} ({{ $v->cid }})</td>
                                    <td>{{ $v->rating_short }}</td>
                                    <td>{{ $v->email }}</td>
                                    <td>{{ $v->home }}</td>
                                    <td>{{ $v->updated_at }}</td>
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
                                            {{ html()->form()->route('rejectVisitRequest', [$v->id])->open() }}
                                            @csrf
                                            <div class="modal-body">
                                                <label for="reject_reason">Please specific why the visit request is being rejected. This will be sent to the requesting visitor with a rejection notification.</label>
                                                {{ html()->textarea('reject_reason', 'Your visit request has been rejected.')->placeholder('Required')->class(['form-control']) }}
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button action="submit" class="btn btn-success">Confirm</button>
                                                </div>
                                                {{ html()->form()->close() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    @include('inc.empty_state', ['header' => 'No New Visit Requests', 'body' => 'There are no new visit requests at this time.', 'icon' => 'fa-solid fa-suitcase'])
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="accepted">
                @if($accepted->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th class="text-left" scope="col">Name (CID)</th>
                                <th scope="col">Rating</th>
                                <th scope="col">Email</th>
                                <th scope="col">Home ARTCC/Division</th>
                                <th scope="col">Accepted at</th>
                                <th scope="col">Accepted by</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accepted as $v)
                                <tr class="text-center">
                                    <td class="text-left" class="text-center" data-toggle="tooltip" title="{{ $v->reason }}">{{ $v->name }} ({{ $v->cid }})</td>
                                    <td>{{ $v->rating_short }}</td>
                                    <td>{{ $v->email }}</td>
                                    <td>{{ $v->home }}</td>
                                    <td>{{ $v->updated_at }}</td>
                                    <td>{{ App\User::find($v->updated_by)->full_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    @include('inc.empty_state', ['header' => 'No Accepted Visit Requests', 'body' => 'There are no previously accepted visit requests at this time.', 'icon' => 'fa-solid fa-suitcase'])
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="rejected">
                @if($rejected->count() > 0)
                    <table class="table table-striped">
                        <thead>
                                <tr class="text-center">
                                <th class="text-left" scope="col">Name (CID)</th>
                                <th scope="col">Rating</th>
                                <th scope="col">Email</th>
                                <th scope="col">Home ARTCC/Division</th>
                                <th scope="col">Rejected at</th>
                                <th scope="col">Rejected by</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rejected as $v)
                                <tr class="text-center">
                                    <td class="text-left" data-toggle="tooltip" title="{{ $v->reason }}">{{ $v->name }} ({{ $v->cid }})</td>
                                    <td>{{ $v->rating_short }}</td>
                                    <td>{{ $v->email }}</td>
                                    <td>{{ $v->home }}</td>
                                    <td>{{ $v->updated_at }}</td>
                                    <td>{{ App\User::find($v->updated_by)->full_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    @include('inc.empty_state', ['header' => 'No Rejected Visit Requests', 'body' => 'There are no previously rejected visit requests at this time.', 'icon' => 'fa-solid fa-suitcase'])
                @endif
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
                    {{ html()->form()->route('manualAddVisitor')->open() }}
                    @csrf
                    <div class="modal-body">
                        <div class="container">
                            <div class="form-group">
                                <div class="row">
                                    <label for="cid">Controller CID</label>
                                    {{ html()->text('cid', null)->placeholder('Controller CID')->class(['form-control']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button action="submit" class="btn btn-success">Search CID</button>
                    </div>
                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
