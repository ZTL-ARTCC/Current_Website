@extends('layouts.dashboard')

@section('title')
Roster Management
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Roster Management</h2>
    &nbsp;
</div>
<br>

<div class="container">
    <h5>Certification Key:</h5>
    <div class="row">
        <div class="col-sm-2">
            <p>No Certification:</p>
        </div>
        <div class="col-sm-2">
            <i class="fas fa-times" style="color:red"></i>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <p>Minor Certification:</p>
        </div>
        <div class="col-sm-2">
            <i class="far fa-check-circle" style="color:green"></i>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <p>Full (Minor/Major) Certification:</p>
        </div>
        <div class="col-sm-2">
            <i class="fas fa-check" style="color:green"></i>
        </div>
    </div>
    <br>
    @if(Auth::user()->can('roster'))
        <a href="/dashboard/admin/roster/visit/requests" class="btn btn-warning">Visit Requests</a>
        <a href="/dashboard/admin/roster/purge-assistant" class="btn btn-danger">Roster Purge Assistant</a>
        <br><br>
    @endif
    <hr>
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#home" role="tab" data-toggle="tab" style="color:black">Home Controllers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#visit" role="tab" data-toggle="tab" style="color:black">Visiting Controllers</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col"><center>Rating</center></th>
                        <th scope="col"><center>Status</center></th>
                        <th scope="col"><center>Delivery</center></th>
                        <th scope="col"><center>Ground</center></th>
                        <th scope="col"><center>Tower</center></th>
                        <th scope="col"><center>Approach</center></th>
                        <th scope="col"><center>Center</center></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hcontrollers as $c)
                        <tr>
                            <td><a href="/dashboard/admin/roster/edit/{{ $c->id }}">{{ $c->backwards_name }}</a></td>
                            <td><center>{{ $c->rating_short }}</center></td>
                            <td><center>{{ $c->status_text }}</center></td>
                            @if($c->del == 0)
                                <td><center>
                                    <i class="fas fa-times" style="color:red"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/del/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->del == 1)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/del/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Revoke Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="far fa-check-circle" style="color:green"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/del/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Major Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->del == 2)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/del/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="fas fa-check" style="color:green"></i>
                                </center></td>
                            @endif
                            @if($c->gnd == 0)
                                <td><center>
                                    <i class="fas fa-times" style="color:red"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/gnd/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->gnd == 1)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/gnd/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Revoke Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="far fa-check-circle" style="color:green"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/gnd/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Major Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->gnd == 2)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/gnd/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="fas fa-check" style="color:green"></i>
                                </center></td>
                            @endif
                            @if($c->twr == 0)
                                <td><center>
                                    <i class="fas fa-times" style="color:red"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/twr/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->twr == 1)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/twr/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Revoke Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="far fa-check-circle" style="color:green"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/twr/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Major Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->twr == 2)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/twr/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="fas fa-check" style="color:green"></i>
                                </center></td>
                            @endif
                            @if($c->app == 0)
                                <td><center>
                                    <i class="fas fa-times" style="color:red"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/app/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->app == 1)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/app/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Revoke Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="far fa-check-circle" style="color:green"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/app/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Major Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->app == 2)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/app/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="fas fa-check" style="color:green"></i>
                                </center></td>
                            @endif
                            @if($c->ctr == 0)
                                <td><center>
                                    <i class="fas fa-times" style="color:red"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/ctr/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Add Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->ctr == 1)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/ctr/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Revoke Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="fas fa-check" style="color:green"></i>
                                </center></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="visit">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col"><center>Rating</center></th>
                        <th scope="col"><center>Status</center></th>
                        <th scope="col"><center>Delivery</center></th>
                        <th scope="col"><center>Ground</center></th>
                        <th scope="col"><center>Tower</center></th>
                        <th scope="col"><center>Approach</center></th>
                        <th scope="col"><center>Center</center></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vcontrollers as $c)
                        <tr>
                            <td><a href="/dashboard/admin/roster/edit/{{ $c->id }}">{{ $c->backwards_name }}</a></td>
                            <td><center>{{ $c->rating_short }}</center></td>
                            <td><center>{{ $c->status_text }}</center></td>
                            @if($c->del == 0)
                                <td><center>
                                    <i class="fas fa-times" style="color:red"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/del/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->del == 1)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/del/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Revoke Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="far fa-check-circle" style="color:green"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/del/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Major Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->del == 2)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/del/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="fas fa-check" style="color:green"></i>
                                </center></td>
                            @endif
                            @if($c->gnd == 0)
                                <td><center>
                                    <i class="fas fa-times" style="color:red"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/gnd/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->gnd == 1)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/gnd/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Revoke Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="far fa-check-circle" style="color:green"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/gnd/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Major Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->gnd == 2)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/gnd/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="fas fa-check" style="color:green"></i>
                                </center></td>
                            @endif
                            @if($c->twr == 0)
                                <td><center>
                                    <i class="fas fa-times" style="color:red"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/twr/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->twr == 1)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/twr/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Revoke Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="far fa-check-circle" style="color:green"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/twr/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Major Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->twr == 2)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/twr/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="fas fa-check" style="color:green"></i>
                                </center></td>
                            @endif
                            @if($c->app == 0)
                                <td><center>
                                    <i class="fas fa-times" style="color:red"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/app/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->app == 1)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/app/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Revoke Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="far fa-check-circle" style="color:green"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/app/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Major Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->app == 2)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/app/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Minor Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="fas fa-check" style="color:green"></i>
                                </center></td>
                            @endif
                            @if($c->ctr == 0)
                                <td><center>
                                    <i class="fas fa-times" style="color:red"></i>
                                    &nbsp;
                                    <a href="/dashboard/admin/roster/controlling-power/add/ctr/{{ $c->id }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Add Rating"><i class="fas fa-plus"></i></a>
                                </center></td>
                            @elseif($c->ctr == 1)
                                <td><center>
                                    <a href="/dashboard/admin/roster/controlling-power/remove/ctr/{{ $c->id }}" class="btn btn-danger btn-sm simple-tooltip" data-toggle="tooltip" title="Revoke Rating"><i class="fa fa-minus"></i></a>
                                    &nbsp;
                                    <i class="fas fa-check" style="color:green"></i>
                                </center></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection