@extends('layouts.master')

@section('title')
Roster
@endsection

@push('custom_header')
<link rel="stylesheet" href="{{ asset('css/roster.css') }}" />
@endpush

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
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#hcontrollers" role="tab" data-toggle="tab" style="color:black"><i class="fas fa-home"></i>&nbsp;Home Controllers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#vcontrollers" role="tab" data-toggle="tab" style="color:black"><i class="fas fa-suitcase"></i>&nbsp;Visiting Controllers</a>
        </li>
    </ul>
    @php
    $tabs = ['hcontrollers', 'vcontrollers'];
    @endphp
    <div class="tab-content">
        @foreach($tabs as $tab)
        @if($loop->first)
        <div role="tabpanel" class="tab-pane active" id="{{ $tab }}">
            @else
            <div role="tabpanel" class="tab-pane" id="{{ $tab }}">
                @endif
                <table class="table table-bordered table-striped">
                    <thead class="sticky">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col" class="text-center">Initials</th>
                            <th scope="col" class="text-center">Rating</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Unrestricted<br>Fields</th>
                            <th scope="col" class="text-center">CLT<br>Tier 1</th>
                            <th scope="col" class="text-center">ATL<br> Tier 1</th>
                            <th scope="col" class="text-center">ZTL<br>Enroute</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($$tab as $c)
                        <tr>
                            <td>
                                @if($c->hasRole('atm'))
                                <span class="badge badge-danger">ATM</span>
                                @elseif($c->hasRole('datm'))
                                <span class="badge badge-danger">DATM</span>
                                @elseif($c->hasRole('ta'))
                                <span class="badge badge-danger">TA</span>
                                @elseif($c->hasRole('wm'))
                                <span class="badge badge-primary">WM</span>
                                @elseif($c->hasRole('awm'))
                                <span class="badge badge-primary">AWM</span>
                                @elseif($c->hasRole('ec'))
                                <span class="badge badge-primary">EC</span>
                                @elseif($c->hasRole('aec'))
                                <span class="badge badge-primary">AEC</span>
                                @elseif($c->hasRole('fe'))
                                <span class="badge badge-primary">FE</span>
                                @elseif($c->hasRole('afe'))
                                <span class="badge badge-primary">AFE</span>
                                @elseif($c->hasRole('ins'))
                                <span class="badge badge-info">INS</span>
                                @elseif($c->hasRole('mtr'))
                                <span class="badge badge-info">MTR</span>
                                @endif
                                {{ $c->backwards_name }}
                            </td>
                            <td class="text-center">{{$c->initials}}</td>
                            <td class="text-center">{{ $c->rating_short }}</td>
                            <td class="text-center">{{ $c->status_text }}</td>
                            <!-- Unrestricted -->
                            <td class="text-center">
                                @if($c->gnd > 0)
                                <span class="badge badge-primary">DEL</span>
                                <span class="badge badge-success">GND</span>
                                @endif
                                @if($c->twr == 99)
                                <span class="badge badge-warning text-light" data-toggle="tooltip" data-html="true" title="Cert Expires: {{ $c->solo }}<br>{{$c->twr_solo_fields}}<br>Auth Expires: {{$c->twr_solo_expires}}">GND-SOLO</span>
                                @elseif($c->twr > 0)
                                <span class="badge badge-danger">TWR</span>
                                @endif
                                @if($c->app == 99)
                                <span class="badge badge-warning text-light" data-toggle="tooltip" style="color:#c1ad13" title="Cert Expires: {{ $c->solo }}<br>{{$c->twr_solo_fields}}<br>Auth Expires: {{$c->twr_solo_expires}}">APP-SOLO</span>
                                @elseif($c->app > 0)
                                <span class="badge badge-info">APP</span>
                                @endif
                            </td>
                            <!-- CLT Tier 1 -->
                            <td class="text-center">
                                @if(($c->clt_gnd > 0)||($c->gnd == 2))
                                <span class="badge badge-primary">DEL</span>
                                <span class="badge badge-success">GND</span>
                                @endif
                                @if(($c->clt_twr > 0)||($c->twr == 2))
                                <span class="badge badge-danger">TWR</span>
                                @endif
                                @if(($c->clt_app > 0)||($c->app > 1))
                                <span class="badge badge-info">APP</span>
                                @endif
                            </td>
                            <!-- ATL Tier 1 -->
                            <td class="text-center">
                                @if(($c->atl_gnd > 0)||($c->gnd == 2))
                                <span class="badge badge-primary">DEL</span>
                                <span class="badge badge-success">GND</span>
                                @endif
                                @if(($c->atl_twr > 0)||($c->twr == 2))
                                <span class="badge badge-danger">TWR</span>
                                @endif
                                @if(($c->atl_app > 0)||($c->app > 1))
                                <span class="badge badge-info">
                                    @if($c->app == 90)
                                    SAT
                                    @elseif($c->app == 91)
                                    DR
                                    @elseif($c->app == 92)
                                    TAR
                                    @else
                                    APP
                                    @endif
                                </span>
                                @endif
                            </td>
                            <!-- Enroute -->
                            <td class="text-center">
                                @if($c->ctr == 99)
                                <span class="badge badge-warning text-light" data-toggle="tooltip" title="Cert Expires: {{ $c->solo }}<br>{{$c->twr_solo_fields}}<br>Auth Expires: {{$c->twr_solo_expires}}">ZTL-SOLO</span>
                                @elseif($c->ctr > 0)
                                <span class="badge badge-secondary">ZTL</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        </div>
    </div>
    @endsection