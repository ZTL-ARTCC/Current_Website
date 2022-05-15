@extends('layouts.master')

@section('title')
Home
@endsection

@section('content')
<div class="jumbotron jumbotron-fluid" style="background-image:url(/photos/ZTL_Banner3.jpg); background-size:cover; background-repeat:no-repeat;">
    <div class="container">
            <div class="row">
                <div class="col-sm-8 text-white">
                    <h1><b>Atlanta Virtual ARTCC</b></h1>
                </div>
            </div>
    </div>
</div>
<div class="container-fluid" style="min-height:20px; width:100%; background-color: #262626; background-image: linear-gradient(180deg, #262626, #262626);">XXX</div> <!-- #262626, #262626 -->
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
			<div class="row">
			<div class="col mt-0 mx-1 mb-1 p-1 rounded bg-dark text-white">
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
				@if($airports->count() > 0)
					@foreach($airports as $a)
						<div><a href="/pilots/airports/view/{{ $a->id }}">{{ $a->ltr_4 }}</a>&nbsp;
						@if($a->visual_conditions == 'VFR')
							<span class="badge bg-success">VFR</span>
						@elseif($a->visual_conditions == 'IFR')
							<span class="badge bg-danger">IFR</span>
						@else
							<span class="badge bg-warning">{{ $a->visual_conditions }}</span>
						@endif
						&nbsp;{{ $a->wind }}&nbsp;{{ $a->altimeter }}</div>
					@endforeach
				@else
					<div class="text-center"><i>No Airports to Show</i></div>
				@endif
				@if($metar_last_updated != null)
					<div class="text-right"><i class="fas fa-sync-alt fa-spin"></i> Last Updated {{ $metar_last_updated }}Z</div>
				@endif
				</div>
			</div>
			<div class="row">
				<div class="col m-1 p-1 rounded bg-dark text-white">
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
