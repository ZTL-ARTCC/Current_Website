@extends('layouts.master')

@section('title')
Home
@endsection

@section('content')
<div class="jumbotron jumbotron-fluid" style="background-image:url(/photos/ZTL_Banner3.jpg); background-size:cover; background-repeat:no-repeat; margin-bottom: 0px">
    <div class="container">
            <div class="row">
                <div class="col-sm-8 text-white">
                    <h1><b>Atlanta Virtual ARTCC</b></h1>
                </div>
            </div>
    </div>
</div>
<div class="container-fluid" style="min-height:30px; width:100%; background-color:#343a40; background-image: linear-gradient(180deg, #343a40, #6c757d); margin-bottom: 10px"></div>
<div class="container-fluid bg-secondary">
    <div class="row">
		<div class="col-sm-9">
<div id="eventCarousel" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
            @if($events->count() > 0)
                @foreach($events as $e)
			<div class="carousel-item @if ($loop->first) active @endif">
                    <a href="/dashboard/controllers/events/view/{{ $e->id }}"><img src="{{ $e->banner_path }}" class="d-block w-100 rounded" alt="{{ $e->name }}" /></a>
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
			<div class="row pr-3">
				<div class="col mt-0 mb-1 mx-1 p-2 rounded bg-dark text-white">
					<div class="row"><div class="col"><h4>Airspace Status</h4></div></div>
					<div class="row">
						<div class="col-auto">
						@if($atl_ctr === 1)
							<span class="badge bg-success">ONLINE</span>
						@else
							<span class="badge bg-danger">OFFLINE</span>
						@endif
						</div><div class="col-auto pl-0">Atlanta Center</div>
					</div>
					<div class="row">
						<div class="col-auto">
						@if($atl_app === 1)
							<span class="badge bg-success">ONLINE</span>
						@else
							<span class="badge bg-danger">OFFLINE</span>
						@endif
						</div><div class="col-auto pl-0">A80 TRACON</div>
					</div>
					<div class="row">
						<div class="col-auto">
						@if($atl_twr === 1)
							<span class="badge bg-success">ONLINE</span>
						@else
							<span class="badge bg-danger">OFFLINE</span>
						@endif
						</div><div class="col-auto pl-0">Atlanta ATCT</div>
					</div>
					<div class="row">
						<div class="col-auto">
						@if($clt_twr === 1)
							<span class="badge bg-success">ONLINE</span>
						@else
							<span class="badge bg-danger">OFFLINE</span>
						@endif
						</div><div class="col-auto pl-0">Charlotte ATCT</div>
					</div>
					<div class="row">
						<div class="col-auto">
							<span class="badge bg-info pr-10">{{ $flights->count() }}</span>&nbsp;&nbsp;flights in ZTL airspace
						</div>
					</div>
				</div>
			</div>

			<div class="row pr-3">
				<div class="col m-1 p-2 rounded bg-dark text-white">
					<div class="row"><div class="col-auto"><h4>Weather</h4></div></div>
				@if($airports->count() > 0)
					@foreach($airports as $a)
						<div class="row">
						<div class="col-auto">
						@if($a->visual_conditions == 'VFR')
							<span class="badge bg-success">VFR</span>
						@elseif($a->visual_conditions == 'IFR')
							<span class="badge bg-danger">IFR</span>
						@else
							<span class="badge bg-warning">{{ $a->visual_conditions }}</span>
						@endif
						</div>
						<div class="col-auto"><a href="/pilots/airports/view/{{ $a->id }}">{{ $a->ltr_4 }}</a></div>
						<div class="col-auto">{{ $a->wind }}&nbsp;{{ $a->altimeter }}</div>
						</div>
					@endforeach
				@else
					<div class="row"><div class="col-auto text-center"><i>No Airports to Show</i></div></div>
				@endif
				@if($metar_last_updated != null)
					<div class="row"><div class="col align-self-end text-right"><i class="fas fa-sync-alt fa-spin"></i> Last Updated {{ $metar_last_updated }}Z</div></div>
				@endif
				</div>
			</div>
			<div class="row pr-3">
				<div class="col m-1 p-2 rounded bg-dark text-white">
					<h4>News</h4>
					@if(count($news) > 0)
						@foreach($news as $c)
							<p>{{ $c->date }} - <b>{{ $c->title }}</b></p>
						@endforeach
					@else
					<center><i><p>No news to show.</p></i></center>
					@endif
					<hr>
					<h4>Calendar</h4>
					@if(count($calendar) > 0)
						@foreach($calendar as $c)
							<p>{{ $c->date }} ({{ $c->time }}) - <b>{{ $c->title }}</b></p>
						@endforeach
					@else
						<center><i><p>No calendar events to show.</p></i></center>
					@endif
				</div>
			</div>
		</div>
	</div> <!-- Carousel row -->
 </div>
@endsection
