@extends('layouts.master')

@section('title')
Home
@endsection

@section('content')
<div class="jumbotron" style="background-image:url(/photos/ZTL_Banner.jpg); background-size:cover; background-repeat:no-repeat;">
    <div class="container">

            <div class="row">
                <div class="col-sm-8 text-white">
                    <h1><b>Atlanta Virtual ARTCC</b></h1>
                </div>

            </div>

    </div>
</div>
<div class="container">
    <hr>
    <div class="row">
		<div class="col-sm-9">
<div id="eventCarousel" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
            @if($events->count() > 0)
                @foreach($events as $e)
			<div class="carousel-item @if ($loop->first) active @endif">
                    <a href="/dashboard/controllers/events/view/{{ $e->id }}"><img src="{{ $e->banner_path }}" class="d-block w-100" alt="{{ $e->name }}" /></a>
            </div>
                @endforeach
            @else
			<div class="carousel-item">
                <center><i><p>No events to show.</p></i></center>
			</div>
            @endif
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>	
<script>	
$('.carousel').carousel({
  interval: 3000 // delay time ms
})	
</script>	
		</div>
		<div class="col">
			<div class="row">
			<div class="col m-1 p-1 rounded bg-dark text-white">
			<h4>Airspace Status</h4>
			@if($atl_ctr === 1)
                <div><span class="badge bg-success">ONLINE</span>&nbsp;Atlanta Center</div>
            @else
				<div><span class="badge bg-danger">OFFLINE</span>&nbsp;Atlanta Center</div>
            @endif
            @if($atl_app === 1)
				<div><span class="badge bg-success">ONLINE</span>&nbsp;A80 TRACON</div>
            @else
				<div><span class="badge bg-danger">OFFLINE</span>&nbsp;A80 TRACON</div>
            @endif
            @if($atl_twr === 1)
				<div><span class="badge bg-success">ONLINE</span>&nbsp;Atlanta ATCT</div>
            @else
				<div><span class="badge bg-danger">OFFLINE</span>&nbsp;Atlanta ATCT</div>
            @endif
            @if($clt_twr === 1)
				<div><span class="badge bg-success">ONLINE</span>&nbsp;Charlotte ATCT</div>
            @else
				<div><span class="badge bg-danger">OFFLINE</span>&nbsp;Charlotte ATCT</div>
            @endif
			@if($flights->count() > 0)
				<div><span class="badge bg-info">{{ $flights->count() }}</span>&nbsp;flights in ZTL airspace</div>
			@endif
			</div>
			</div>
			<div class="row">
			<div class="col m-1 p-1 rounded bg-dark text-white">
			<h4>Weather</h4>
			</div>
			</div>
			<div class="row">
			<div class="col m-1 p-1 rounded bg-dark text-white">
			<h4>News/Calendar</h4>
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
        </div>
    </div>
			</div>
			</div>
		</div>
		
	</div> <!-- Carousel row -->
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
    </div>
 </div>
@endsection
