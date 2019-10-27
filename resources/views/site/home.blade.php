@extends('layouts.master')

@section('title')
Home
@endsection
@section('content')
<div class="main-section">
<section class="hero-wrap js-fullheight img" style="background-image: url(/photos/ZTL_Banner.jpg);">
  		<div class="overlay"></div>
  		<div class="container">
  			<div class="row description js-fullheight align-items-center justify-content-center">
  				<div class="col-md-8 text-center">
  					<div class="text">
  						<h1>vZTL ARTCC.</h1>
  						<h4 class="mb-5">ARTCC Within VATUSA & VATSIM</h4>
  						<p><a href="#" class="btn btn-primary px-4 py-3">Why us? Join?</a></p>
  					</div>
  				</div>
  			</div>
  		</div>
  	</section>
      <section>
    <div class="container">
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <center><h4><i class="fas fa-newspaper"></i> News</h4></center>
            @if(count($news) > 0)
                @foreach($news as $c)
                    <p>{{ $c->date }} - <b>{{ $c->title }}</b></p>
                @endforeach
            @else
                <center><i><p>No news to show.</p></i></center>
            @endif
        </div>
        <div class="col-sm-4">
            <center><h4><i class="fas fa-calendar-alt"></i> Calendar</h4></center>
            @if(count($calendar) > 0)
                @foreach($calendar as $c)
                    <p>{{ $c->date }} ({{ $c->time }}) - <b>{{ $c->title }}</b></p>
                @endforeach
            @else
                <center><i><p>No calendar events to show.</p></i></center>
            @endif
        </div>
        <div class="col-sm-4">
            <center><h4><i class="fas fa-plane"></i> Events</h4></center>
            @if($events->count() > 0)
                @foreach($events as $e)
                    <img src="{{ $e->banner_path }}" width="100%" alt="{{ $e->name }}">
                    <p></p>
                @endforeach
            @else
                <center><i><p>No events to show.</p></i></center>
            @endif
        </div>
    </div>
</div>
</section>
<section>
    <div class ="container">

    <hr>
    <div class="row">
        <div class="col-sm-6">
            <center><h4><i class="fa fa-cloud"></i> Weather</h4></center>
            <div class="table">
                <table class="table table-bordered table-sm">
                    <thead>
                        <th scope="col"><center>Airport</center></th>
                        <th scope="col"><center>Conditions</center></th>
                        <th scope="col"><center>Wind</center></th>
                        <th scope="col"><center>Altimeter</center></th>
                    </thead>
                    <tbody>
                        @if($airports->count() > 0)
                            @foreach($airports as $a)
                                <tr>
                                    <td><a href="/pilots/airports/view/{{ $a->id }}"><center>{{ $a->ltr_4 }}</center></a></td>
                                    <td><center>{{ $a->visual_conditions }}</center></td>
                                    <td><center>{{ $a->wind }}</center></td>
                                    <td><center>{{ $a->altimeter }}</center></td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="4"><div align="center"><i>No Airports to Show</i></div></td>
                        @endif
                        <tr>
                            @if($metar_last_updated != null)
                                <td colspan="4"><div align="right"><i class="fas fa-sync-alt fa-spin"></i> Last Updated {{ $metar_last_updated }}Z</div></td>
                            @else
                                <td colspan="4"><div align="right"><i class="fas fa-sync-alt fa-spin"></i> Last Updated N/A</div></td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-6">
            <center><h4><i class="fa fa-broadcast-tower"></i> Online Controllers</h4></center>
            <div class="table">
                <table class="table table-bordered table-sm">
                    <thead>
                        <th scope="col"><center>Position</center></th>
                        <th scope="col"><center>Frequency</center></th>
                        <th scope="col"><center>Controller</center></th>
                        <th scope="col"><center>Time Online</center></th>
                    </thead>
                    <tbody>
                        @if($controllers->count() > 0)
                            @foreach($controllers as $c)
                                <tr>
                                    <td><center>{{ $c->position }}</center></td>
                                    <td><center>{{ $c->freq }}</center></td>
                                    <td><center>{{ $c->name }}</center></td>
                                    <td><center>{{ $c->time_online }}</center></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4"><center><i>No Controllers Online</i></center></td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="4"><div align="right"><i class="fas fa-sync-alt fa-spin"></i> Last Updated {{ $controllers_update }}Z</div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</section>
<section>
    <div class ="container">
    <hr>
    <center><h4><i class="fa fa-plane"></i> Flights Currently Within ZTL Airspace</h4></center>
    <div class="table">
        <table class="table table-bordered table-sm">
            <thead>
                <th scope="col"><center>Callsign</center></th>
                <th scope="col"><center>Pilot Name</center></th>
                <th scope="col"><center>Aircraft Type</center></th>
                <th scope="col"><center>Departure</center></th>
                <th scope="col"><center>Arrival</center></th>
                <th scope="col"><center>Route</center></th>
            </thead>
            <tbody>
                @if($flights->count() > 0)
                    @foreach($flights as $c)
                        <tr>
                            <td><center>{{ $c->callsign }}</center></td>
                            <td><center>{{ $c->pilot_name }}</center></td>
                            <td><center>{{ $c->type }}</center></td>
                            <td><center>{{ $c->dep }}</center></td>
                            <td><center>{{ $c->arr }}</center></td>
                            <td><center>{{ str_limit($c->route, 50) }}</center></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6"><center><i>No Pilots in ZTL Airspace</i></center></td>
                    </tr>
                @endif
                <tr>
                    <td colspan="6"><div align="right"><i class="fas fa-sync-alt fa-spin"></i> Last Updated {{ $flights_update }}Z</div></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
</section>
</div>
@endsection
