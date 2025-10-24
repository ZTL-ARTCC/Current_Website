@extends('layouts.master')

@section('title')
View Airport ({{ $airport->ltr_4 }})
@endsection

@push('custom_header')
@vite('resources/css/airports.css')
@endpush

@section('content')
@include('inc.header', ['title' => $airport->name . ' (' . $airport->ltr_3 . ')', 'type' => 'external'])

<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <b>Airport Diagram</b> <div class="float-end"></div>
                </div>
                <div class="card-body">
                    @if($apd != '[]')
                        @foreach($apd as $c)
                            <iframe src="{{ $c->pdf_url }}" frameborder="0" style="width:100%; height:750px"></iframe>
                        @endforeach
                    @else
                        <p>No airport diagram found</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <b>Current Weather/Forecast ({{ $airport->visual_conditions }} Conditions)</b>
                </div>
                <div class="card-body">
                    {{ $airport->metar }}
                    <hr>
                    {!! $airport->taf !!}
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    <b>All Charts</b>
                </div>
                <div class="card-body">
                    @if($charts != null)
                        @if($apd != '[]' || $general != '[]')
                            <div class="card">
                                <div class="collapsible">
                                    <div class="card-header">
                                        General
                                    </div>
                                </div>
                                <div class="content">
                                    <div class="card-body" style="max-height:400px;overflow-y:auto;">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Chart Name</th>
                                                    <th scope="col">View</th>
                                                </tr>
                                                @if($apd != '[]')
                                                    @foreach($apd as $c)
                                                        <tr>
                                                            <td>{{ $c->chart_name }}</td>
                                                            <td>
                                                                <a href="{{ $c->pdf_url }}" class="btn btn-success btn-sm simple-tooltip" data-bs-toggle="tooltip" title="View {{ $c->chart_name }}" target="_blank"><i class="fas fa-eye"></i></a>
                                                                @if($c->did_change)
                                                                    <a href="{{ $c->change_pdf_url }}" class="btn btn-warning btn-sm simple-tooltip" data-bs-toggle="tooltip" title="View {{ $c->chart_name }} Changes" target="_blank"><i class="fas fa-file-pen"></i></a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                @if($general != '[]')
                                                    @foreach($general as $c)
                                                        <tr>
                                                            <td>{{ $c->chart_name }}</td>
                                                            <td>
                                                                <a href="{{ $c->pdf_url }}" class="btn btn-success btn-sm simple-tooltip" data-bs-toggle="tooltip" title="View {{ $c->chart_name }}" target="_blank"><i class="fas fa-eye"></i></a>
                                                                @if($c->did_change)
                                                                    <a href="{{ $c->change_pdf_url }}" class="btn btn-warning btn-sm simple-tooltip" data-bs-toggle="tooltip" title="View {{ $c->chart_name }} Changes" target="_blank"><i class="fas fa-file-pen"></i></a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endif
                        @if($dp != '[]')
                            <div class="card">
                                <div class="collapsible">
                                    <div class="card-header">
                                        Departures
                                    </div>
                                </div>
                                <div class="content">
                                    <div class="card-body" style="max-height:400px;overflow-y:auto;">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Chart Name</th>
                                                    <th scope="col">View</th>
                                                </tr>
                                                @foreach($dp as $c)
                                                    <tr>
                                                        <td>{{ $c->chart_name }}</td>
                                                        <td>
                                                            <a href="{{ $c->pdf_url }}" class="btn btn-success btn-sm simple-tooltip" data-bs-toggle="tooltip" title="View {{ $c->chart_name }}" target="_blank"><i class="fas fa-eye"></i></a>
                                                                @if($c->did_change)
                                                                    <a href="{{ $c->change_pdf_url }}" class="btn btn-warning btn-sm simple-tooltip" data-bs-toggle="tooltip" title="View {{ $c->chart_name }} Changes" target="_blank"><i class="fas fa-file-pen"></i></a>
                                                                @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endif
                        @if($star != '[]')
                            <div class="card">
                                <div class="collapsible">
                                    <div class="card-header">
                                        Arrivals
                                    </div>
                                </div>
                                <div class="content">
                                    <div class="card-body" style="max-height:400px;overflow-y:auto;">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Chart Name</th>
                                                    <th scope="col">View</th>
                                                </tr>
                                                @foreach($star as $c)
                                                    <tr>
                                                        <td>{{ $c->chart_name }}</td>
                                                        <td>
                                                            <a href="{{ $c->pdf_url }}" class="btn btn-success btn-sm simple-tooltip" data-bs-toggle="tooltip" title="View {{ $c->chart_name }}" target="_blank"><i class="fas fa-eye"></i></a>
                                                                @if($c->did_change)
                                                                    <a href="{{ $c->change_pdf_url }}" class="btn btn-warning btn-sm simple-tooltip" data-bs-toggle="tooltip" title="View {{ $c->chart_name }} Changes" target="_blank"><i class="fas fa-file-pen"></i></a>
                                                                @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endif
                        @if($iap != '[]')
                            <div class="card">
                                <div class="collapsible">
                                    <div class="card-header">
                                        Approaches
                                    </div>
                                </div>
                                <div class="content">
                                    <div class="card-body" style="max-height:400px;overflow-y:auto;">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Chart Name</th>
                                                    <th scope="col">View</th>
                                                </tr>
                                                @foreach($iap as $c)
                                                    <tr>
                                                        <td>{{ $c->chart_name }}</td>
                                                        <td>
                                                            <a href="{{ $c->pdf_url }}" class="btn btn-success btn-sm simple-tooltip" data-bs-toggle="tooltip" title="View {{ $c->chart_name }}" target="_blank"><i class="fas fa-eye"></i></a>
                                                                @if($c->did_change)
                                                                    <a href="{{ $c->change_pdf_url }}" class="btn btn-warning btn-sm simple-tooltip" data-bs-toggle="tooltip" title="View {{ $c->chart_name }} Changes" target="_blank"><i class="fas fa-file-pen"></i></a>
                                                                @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endif
                    @else
                        <p>No charts found for {{ $airport->ltr_4 }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-header">
            <b>Inbound Traffic (Arrivals)</b>
        </div>
        <div class="card-body">
            @if($airport->inbound_traffic != null)
                <table class="table table-striped">
                    <thead>
                        <th scope="col">Callsign</th>
                        <th scope="col">Aircraft</th>
                        <th scope="col">Rules</th>
                        <th scope="col">Departure</th>
                        <th scop="col">Route</th>
                    </thead>
                    <tbody>
                        @foreach($airport->inbound_traffic as $a)
                        <tr>
                            <td>{{ $a['callsign'] }}</td>
                            <td>{{ $a['aircraft'] }}</td>
                            @if($a['flight_type'] == 'I')
                                <td>IFR</td>
                            @else
                                <td>VFR</td>
                            @endif
                            <td>{{ $a['origin'] }}</td>
                            <td>{{ $a['route'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No inbound traffic.</p>
            @endif
			
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-header">
            <b>Outbound Traffic (Departures)</b>
        </div>
        <div class="card-body">
            @if($airport->outbound_traffic != null)
                <table class="table table-striped">
                    <thead>
                        <th scope="col">Callsign</th>
                        <th scope="col">Aircraft</th>
                        <th scope="col">Rules</th>
                        <th scope="col">Destination</th>
                        <th scop="col">Route</th>
                    </thead>
                    <tbody>
                        @foreach($airport->outbound_traffic as $a)
                        <tr>
                            <td>{{ $a['callsign'] }}</td>
                            <td>{{ $a['aircraft'] }}</td>
                            @if($a['flight_type'] == 'I')
                                <td>IFR</td>
                            @else
                                <td>VFR</td>
                            @endif
                            <td>{{ $a['destination'] }}</td>
                            <td>{{ $a['route'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No outbound traffic.</p>
            @endif	
        </div>
    </div>
</div>
@vite('resources/js/airports.js')
@endsection
