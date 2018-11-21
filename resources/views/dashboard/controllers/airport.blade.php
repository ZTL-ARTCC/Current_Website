@extends('layouts.dashboard')

@section('title')
{{ $apt_r }} Information
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>{{ $apt_r }} Airport Information</h2>
    &nbsp;
</div>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <b>Airport Diagram</b> <div class="float-right"></div>
                </div>
                <div class="card-body">
                    <img src="http://flightaware.com/resources/airport/{{ $apt_r }}/APD/AIRPORT+DIAGRAM/png" alt="No Airport Diagram. This airport may be an uncontrolled field or it may not be within the United States." width="100%">
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <b>Current Weather/Forecast ({{ $visual_conditions }} Conditions)</b>
                </div>
                <div class="card-body">
                    METAR {{ $metar }}
                    <hr>
                    TAF {{ $taf }}
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    <b>All Charts</b>
                </div>
                <div class="card-body">
                    @if($charts != null)
                        @if($charts->General != null)
                            <div class="card">
                                <div class="collapsible">
                                    <div class="card-header">
                                        General ({{ count($charts->General) }})
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
                                                @foreach($charts->General as $c)
                                                    <tr>
                                                        <td>{{ $c->chartname }}</td>
                                                        <td>
                                                            <a href="{{ $c->url }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Download {{ $c->chartname }}" target="_blank"><i class="fas fa-download"></i></a>
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
                        @if(isset($charts->SID))
                            <div class="card">
                                <div class="collapsible">
                                    <div class="card-header" >
                                        Departures ({{ count($charts->SID) }})
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
                                                @foreach($charts->SID as $c)
                                                    <tr>
                                                        <td>{{ $c->chartname }}</td>
                                                        <td>
                                                            <a href="{{ $c->url }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Download {{ $c->chartname }}" target="_blank"><i class="fas fa-download"></i></a>
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
                        @if(isset($charts->STAR))
                            <div class="card">
                                <div class="collapsible">
                                    <div class="card-header">
                                        Arrivals ({{ count($charts->STAR) }})
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
                                                @foreach($charts->STAR as $c)
                                                    <tr>
                                                        <td>{{ $c->chartname }}</td>
                                                        <td>
                                                            <a href="{{ $c->url }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Download {{ $c->chartname }}" target="_blank"><i class="fas fa-download"></i></a>
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
                        @if(isset($charts->Approach))
                            <div class="card">
                                <div class="collapsible">
                                    <div class="card-header">
                                        Approaches ({{ count($charts->Approach) }})
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
                                                @foreach($charts->Approach as $c)
                                                    <tr>
                                                        <td>{{ $c->chartname }}</td>
                                                        <td>
                                                            <a href="{{ $c->url }}" class="btn btn-success btn-sm simple-tooltip" data-toggle="tooltip" title="Download {{ $c->chartname }}" target="_blank"><i class="fas fa-download"></i></a>
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
                        <p>No charts found for {{ $apt_r }}</p>
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
            @if($pilots_a != null)
                <table class="table table-striped">
                    <thead>
                        <th scope="col">Callsign</th>
                        <th scope="col">Aircraft</th>
                        <th scope="col">Rules</th>
                        <th scope="col">Departure</th>
                        <th scop="col">Route</th>
                    </thead>
                    <tbody>
                        @foreach($pilots_a as $a)
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
            @if($pilots_d != null)
                <table class="table table-striped">
                    <thead>
                        <th scope="col">Callsign</th>
                        <th scope="col">Aircraft</th>
                        <th scope="col">Rules</th>
                        <th scope="col">Destination</th>
                        <th scop="col">Route</th>
                    </thead>
                    <tbody>
                        @foreach($pilots_d as $a)
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
