@extends('layouts.master')

@section('title')
Roster
@endsection

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Roster</h2>
        &nbsp;
    </div>
</span>
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
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#home" role="tab" data-toggle="tab" style="color:black">Home Controllers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#visit" role="tab" data-toggle="tab" style="color:black">Visiting Controllers</a>
        </li>
        <!--<li class="nav-item">
            <a class="nav-link" href="#visitagree" role="tab" data-toggle="tab" style="color:black">ZJX Visiting Controllers</a>
        </li> -->
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col"><center>Initials</center></th>
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
                            <td>
                                @if($c->hasRole('atm'))
                                    <span class="badge badge-danger">ATM</span> {{ $c->backwards_name }}
                                @elseif($c->hasRole('datm'))
                                    <span class="badge badge-danger">DATM</span> {{ $c->backwards_name }}
                                @elseif($c->hasRole('ta'))
                                    <span class="badge badge-danger">TA</span> {{ $c->backwards_name }}
                                @elseif($c->hasRole('wm'))
                                    <span class="badge badge-primary">WM</span> {{ $c->backwards_name }}
                                @elseif($c->hasRole('ec'))
                                    <span class="badge badge-primary">EC</span> {{ $c->backwards_name }}
                                @elseif($c->hasRole('aec'))
                                    <span class="badge badge-primary">AEC</span> {{ $c->backwards_name }}
                                @elseif($c->hasRole('fe'))
                                    <span class="badge badge-primary">FE</span> {{ $c->backwards_name }}
                                @elseif($c->hasRole('afe'))
                                    <span class="badge badge-primary">AFE</span> {{ $c->backwards_name }}
                                @elseif($c->hasRole('ins'))
                                    <span class="badge badge-info">INS</span> {{ $c->backwards_name }}
                                @elseif($c->hasRole('mtr'))
                                    <span class="badge badge-info">MTR</span> {{ $c->backwards_name }}
                                @else
                                    {{ $c->backwards_name }}
                                @endif
                            </td>
                            <td><center>{{$c->initials}}</center></td>
                            <td><center>{{ $c->rating_short }}</center></td>
                            <td><center>{{ $c->status_text }}</center></td>
                            @if($c->del == 0)
                                <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                            @elseif($c->del == 1)
                                <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                            @elseif($c->del == 2)
                                <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                            @endif
                            @if($c->gnd == 0)
                                <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                            @elseif($c->gnd == 1)
                                <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                            @elseif($c->gnd == 2)
                                <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                            @endif
                            @if($c->twr == 0)
                                <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                            @elseif($c->twr == 1)
                                <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                            @elseif($c->twr == 2)
                                <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                            @elseif($c->twr == 99)
                                <td><center><i class="fab fa-stripe-s" data-toggle="tooltip" style="color:#c1ad13" title="Expires: {{ $c->solo }}"></i></center></td>
                            @endif
                            @if($c->app == 0)
                                <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                            @elseif($c->app == 1)
                                <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                            @elseif($c->app == 2)
                                <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                            @elseif($c->app == 90)
                                <td><center><b style="color:blue;">A80 SAT</b></center></td>
                            @elseif($c->app == 91)
                                <td><center><b style="color:blue;">A80 DR</b></center></td>
                            @elseif($c->app == 92)
                                <td><center><b style="color:blue;">A80 TAR</b></center></td>    
                            @elseif($c->app == 99)
                                <td><center><i class="fab fa-stripe-s" data-toggle="tooltip" style="color:#c1ad13" title="Expires: {{ $c->solo }}"></i></center></td>
                            @elseif($c->app == 89)
                                <td><center style="color:#c1ad13">M</center></td>
                            @endif
                            @if($c->ctr == 0)
                                <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                            @elseif($c->ctr == 1)
                                <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                            @elseif($c->ctr == 99)
                                <td><center><i class="fab fa-stripe-s" data-toggle="tooltip" style="color:#c1ad13" title="Expires: {{ $c->solo }}"></i></center></td>
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
                        <th scope="col"><center>Initials</center></th>
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
                        @if($c->status != 2)
                            <tr>
                                <td>{{ $c->backwards_name }} - {{ $c->visitor_from }}</td>
                                <td><center>{{$c->initials}}</center></td>
                                <td><center>{{ $c->rating_short }}</center></td>
                                <td><center>{{ $c->status_text }}</center></td>
                                @if($c->del == 0)
                                    <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                                @elseif($c->del == 1)
                                    <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                                @elseif($c->del == 2)
                                    <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                                @endif
                                @if($c->gnd == 0)
                                    <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                                @elseif($c->gnd == 1)
                                    <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                                @elseif($c->gnd == 2)
                                    <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                                @endif
                                @if($c->twr == 0)
                                    <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                                @elseif($c->twr == 1)
                                    <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                                @elseif($c->twr == 2)
                                    <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                                @endif
                                @if($c->app == 0)
                                    <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                                @elseif($c->app == 1)
                                    <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                                @elseif($c->app == 2)
                                    <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                                @elseif($c->app == 90)
                                    <td><center><b style="color:blue;">A80 SAT</b></center></td>
                                @elseif($c->app == 91)
                                    <td><center><b style="color:blue;">A80 DR</b></center></td>
                                @elseif($c->app == 92)
                                    <td><center><b style="color:blue;">A80 TAR</b></center></td>     
                                @endif
                                @if($c->ctr == 0)
                                    <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                                @elseif($c->ctr == 1)
                                    <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <!--<div role="tabpanel" class="tab-pane" id="visitagree">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col"><center>Initials</center></th>
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
                    @foreach($visagreecontrollers as $c)
                        @if($c->rating_short != "OBS" && $c->status != 2)
                            <tr>
                                <td>{{ $c->backwards_name }} - {{ $c->visitor_from }}</td>
                                <td><center>{{$c->initials}}</center></td>
                                <td><center>{{ $c->rating_short }}</center></td>
                                <td><center>{{ $c->status_text }}</center></td>
                                @if($c->del == 0)
                                    <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                                @elseif($c->del == 1)
                                    <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                                @elseif($c->del == 2)
                                    <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                                @endif
                                @if($c->gnd == 0)
                                    <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                                @elseif($c->gnd == 1)
                                    <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                                @elseif($c->gnd == 2)
                                    <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                                @endif
                                @if($c->twr == 0)
                                    <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                                @elseif($c->twr == 1)
                                    <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                                @elseif($c->twr == 2)
                                    <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                                @endif
                                @if($c->app == 0)
                                    <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                                @elseif($c->app == 1)
                                    <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                                @elseif($c->app == 2)
                                    <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                                @elseif($c->app == 90)
                                    <td><center><b style="color:blue;">A80 SAT</b></center></td>
                                @elseif($c->app == 91)
                                    <td><center><b style="color:blue;">A80 DR</b></center></td>
                                @elseif($c->app == 92)
                                    <td><center><b style="color:blue;">A80 TAR</b></center></td> 
                                @endif
                                @if($c->ctr == 0)
                                    <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                                @elseif($c->ctr == 1)
                                    <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div> -->
    </div>
</div>

@endsection
