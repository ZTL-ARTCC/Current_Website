@extends('layouts.master')

@section('title')
View Airport ({{ $airport->ltr_4 }})
@endsection

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>{{ $airport->name }} Airport ({{ $airport->ltr_3 }})</h2>
        &nbsp;
    </div>
</span>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <b>Airport Diagram</b> <div class="float-right"></div>
                </div>
                <div class="card-body">
                    @if($apd != '[]')
                        @foreach($apd as $c)
                            <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url={{ $c->pdf_path }}" width="100%" height="755px">
                        @endforeach
                    @else
                        <p>No airport diagram found</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <b>Current Weather/Forecast ({{ $airport->visual_conditions }} Conditions)</b>
                </div>
                <div class="card-body">
                    METAR {{ $airport->metar }}
                    <hr>
                    TAF {{ $airport->taf }}
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    <b>All Charts</b>
                </div>
                <div class="card-body">
                    @if($charts != null)
                        @if($apd != '[]' || $min != '[]' || $hot != '[]' || $lah != '[]')
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
                                                    <th scope="col">Download</th>
                                                </tr>
                                                @if($apd != '[]')
                                                    @foreach($apd as $c)
                                                        <tr>
                                                            <td>{{ $c->chart_name }}</td>
                                                            <td>
                                                                <a href="{{ $c->pdf_path }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Download {{ $c->chart_name }}" target="_blank"><i class="fas fa-download"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                @if($min != '[]')
                                                    @foreach($min as $c)
                                                        <tr>
                                                            <td>{{ $c->chart_name }}</td>
                                                            <td>
                                                                <a href="{{ $c->pdf_path }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Download {{ $c->chart_name }}" target="_blank"><i class="fas fa-download"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                @if($hot != '[]')
                                                    @foreach($hot as $c)
                                                        <tr>
                                                            <td>{{ $c->chart_name }}</td>
                                                            <td>
                                                                <a href="{{ $c->pdf_path }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Download {{ $c->chart_name }}" target="_blank"><i class="fas fa-download"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                @if($lah != '[]')
                                                    @foreach($lah as $c)
                                                        <tr>
                                                            <td>{{ $c->chart_name }}</td>
                                                            <td>
                                                                <a href="{{ $c->pdf_path }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Download {{ $c->chart_name }}" target="_blank"><i class="fas fa-download"></i></a>
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
                                                    <th scope="col">Download</th>
                                                </tr>
                                                @foreach($dp as $c)
                                                    <tr>
                                                        <td>{{ $c->chart_name }}</td>
                                                        <td>
                                                            <a href="{{ $c->pdf_path }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Download {{ $c->chart_name }}" target="_blank"><i class="fas fa-download"></i></a>
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
                                                    <th scope="col">Download</th>
                                                </tr>
                                                @foreach($star as $c)
                                                    <tr>
                                                        <td>{{ $c->chart_name }}</td>
                                                        <td>
                                                            <a href="{{ $c->pdf_path }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Download {{ $c->chart_name }}" target="_blank"><i class="fas fa-download"></i></a>
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
                                                    <th scope="col">Download</th>
                                                </tr>
                                                @foreach($iap as $c)
                                                    <tr>
                                                        <td>{{ $c->chart_name }}</td>
                                                        <td>
                                                            <a href="{{ $c->pdf_path }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Download {{ $c->chart_name }}" target="_blank"><i class="fas fa-download"></i></a>
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

<style>
    .collapsible {
    cursor: pointer;
    }
    .content {
        overflow: hidden;
        min-height: 0;
        max-height: 0;
        transition: max-height 0.5s ease-out;
    }
    </style>

    <script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
      coll[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        if (content.style.maxHeight){
          content.style.maxHeight = null;
        } else {
          content.style.maxHeight = content.scrollHeight + "px";
        }
      });
    }
</script>
@endsection
