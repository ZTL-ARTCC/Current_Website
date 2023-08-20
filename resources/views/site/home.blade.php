@extends('layouts.master')

@section('title')
Home
@endsection

@push('custom_header')
	<link rel="stylesheet" href="{{ asset('css/home.css') }}" />
@endpush

@section('content')
<div class="w-100 py-3 bg-secondary">
	<div class="jumbotron jumbotron-fluid rounded mb-0 mx-3" style="background-image:url(/photos/ZTL_Banner3.jpg); background-size:cover; background-repeat:no-repeat">
		<div class="container">
			<div class="row">
				<div class="col-sm-8 text-white">
					<h1><b>Atlanta Virtual ARTCC</b></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid bg-secondary">
	<div class="row">
		<div class="col-sm-9">
			<div id="eventCarousel" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">
					@if($events->count() > 0)
					@foreach($events as $e)
					<div class="carousel-item @if ($loop->first) active @endif">
						@if(Auth::check())
						<a href="/dashboard/controllers/events/view/{{ $e->id }}">
							@else
							<a href="https://forums.vatusa.net/index.php?{{ $e->forum }}" target="_blank">
								@endif
								<img src="{{ $e->banner_path }}" class="d-block w-100 rounded" alt="{{ $e->name }}" />
							</a>
					</div>
					@endforeach
					@else
					<div class="carousel-item active">
						<div class="d-block w-100 h-100 d-flex align-items-center bg-dark rounded" style="min-height:500px">
							<h5 class="text-light w-100 text-center">No events scheduled</br>Please check back soon!</h5>
						</div>
					</div>
					@endif
				</div>
				<a class="carousel-control-prev" href="#eventCarousel" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="carousel-control-next" href="#eventCarousel" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
			</div>
			<script>
				$('.carousel').carousel({
					interval: 5000 // delay time ms
				})
			</script>
		</div>
		<div class="col">
			<div class="row px-2 pr-md-3 pl-md-0">
				<div class="col mt-lg-0 mt-3 mb-1 mx-1 p-2 rounded bg-dark text-white info_card airspace_status" onclick="$('#displayAsx').modal('toggle');">
					<div class="row">
						<div class="col">
							<h4 class="pb-0 mb-0">Airspace Status</h4><span class="text-secondary pt-0 mt-0">Click for map</span>
						</div>
					</div>
					<div class="row">
						<div class="col-auto">
							<table class="table table-sm table-borderless pb-0 mb-0">
								<tr class="p-3 m-0">
									<td class="py-0 pl-1 pr-2 m-0">
										@if($center === 1)
										<span class="badge bg-success">ONLINE</span>
										@else
										<span class="badge bg-danger">OFFLINE</span>
										@endif
									</td>
									<td class="py-0 px-2 m-0 text-white">Atlanta Center</td>
								</tr>

								@foreach ($fields as $field)
									<tr class="p-3 m-0">
										<td class="py-0 pl-1 pr-2 m-0">
											@if($field['online'] === 1)
												<span class="badge bg-success">ONLINE</span>
											@else
												<span class="badge bg-danger">OFFLINE</span>
											@endif
										</td>
										<td class="py-0 px-2 m-0 text-white">
											{{ $field['name'] }} ATCT

											@foreach ($field['subfields'] as $subfield)
												<span class="badge {{ $subfield['color'] }}">{{ $subfield['short'] }}</span>
											@endforeach
										</td>
									</tr>

									<tr class="p-3 m-0">
										<td class="py-0 pl-1 pr-2 m-0">
											@if($field['approach']['online'] === 1)
												<span class="badge bg-success">ONLINE</span>
											@else
												<span class="badge bg-danger">OFFLINE</span>
											@endif
										</td>
										<td class="py-0 px-2 m-0 text-white">{{ $field['name'] }} Approach</td>
									</tr>
								@endforeach

							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-auto">
							<span class="badge bg-info ml-1">{{ $overflightCount }}</span>&nbsp;&nbsp;flights in ZTL airspace
						</div>
					</div>
				</div>
			</div>
			<div class="row px-2 pr-md-3 pl-md-0">
				<div class="col m-1 p-2 rounded bg-dark text-white info_card weather_status">
					<div class="row">
						<div class="col-auto">
							<h4>Weather</h4>
						</div>
					</div>
					<div class="row">
						<div class="col-auto">
							@if($airports->count() > 0)
							<table class="table table-sm table-borderless pb-0 mb-0">
								@foreach($airports as $a)
								<tr class="p-3 m-0">
									<td class="py-0 pl-1 pr-2 m-0">
										@if($a->visual_conditions == 'VFR')
										<span class="badge bg-success">VFR</span>
										@elseif($a->visual_conditions == 'IFR')
										<span class="badge bg-danger">IFR</span>
										@else
										<span class="badge bg-warning">{{ $a->visual_conditions }}</span>
										@endif
									</td>
									<td class="py-0 px-2 m-0"><a href="/pilots/airports/view/{{ $a->id }}">{{ $a->ltr_4 }}</a></td>
									<td class="py-0 px-2 m-0 text-white">{{ $a->wind }}&nbsp;{{ $a->altimeter }}</td>
								</tr>
								@endforeach
							</table>
							@else
							<div class="row">
								<div class="col-auto text-center"><i>No Airports to Show</i></div>
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="row px-2 pr-md-3 pl-md-0">
				<div class="col m-1 p-2 rounded bg-dark text-white info_card news_calendar" onclick="$('#displayNewsCalendar').modal('toggle');">
					<h4>News</h4><span class="text-secondary pt-0 mt-0">Click for details</span>
					@if(count($news) > 0)
					@foreach($news as $c)
					<p>{{ $c->date }} - <b>{{ $c->title }}</b></p>
					@endforeach
					@else
					<center><i>
							<p>No news to show.</p>
						</i></center>
					@endif
					<hr>
					<h4>Calendar</h4>
					@if(count($calendar) > 0)
					@foreach($calendar as $c)
					<p>{{ $c->date }} @if($c->time != '') ({{ $c->time }}) @endif - <b>{{ $c->title }}</b></p>
					@endforeach
					@else
					<center><i>
							<p>No calendar events to show.</p>
						</i></center>
					@endif
				</div>
			</div>
		</div>
	</div> <!-- Carousel row -->
</div>
<!-- Modal -->
<div class="modal fade" id="displayAsx" tabindex="-1" role="dialog" aria-labelledby="displayAsx" aria-hidden="true">
	<div class="modal-dialog" style="height:90vh; max-width:90vw" role="document">
		<div class="modal-content py-1 home-modal-bg">
			<div class="modal-body">
				<button type="button" class="close" style="position:absolute; z-index:100; top:5px; right:15px;" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<embed src="https://ids.ztlartcc.org/asx#dispNode" frameborder="0" style="height:85vh; width:85vw">
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="displayNewsCalendar" tabindex="-1" role="dialog" aria-labelledby="displayNews" aria-hidden="true">
	<div class="modal-dialog" style="height:50vh; max-width:50vw" role="document">
		<div class="modal-content py-1 home-modal-bg">
			<div class="modal-body">
				<button type="button" class="close" style="position:absolute; z-index:100; top:5px; right:15px;" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="text-body"><i class="fas fa-newspaper"></i>&nbsp;ZTL News</h4>
				<hr>
				@if(count($news) > 0)
				@foreach($news as $c)
				<h5 class="text-body"><u>{{ $c->title }}</u></h5>
				<p class="text-light">
					{!! $c->body !!}
					Posted by: {{ App\User::find($c->created_by)->full_name }} at {{ $c->created_at }}
				</p>
				@endforeach
				@else
				<center><i>
						<p>No news to show.</p>
					</i></center>
				@endif
				<hr>
				<h4 class="text-body"><i class="fas fa-calendar"></i>&nbsp;ZTL Calendar</h4>
				<hr>
				@if(count($calendar) > 0)
				@foreach($calendar as $c)
				<h5 class="text-body"><u>{{ $c->title }}</u></h5>
				<p class="text-light">
					{!! $c->body !!}
					Posted by: {{ App\User::find($c->created_by)->full_name }} at {{ $c->created_at }}
				</p>
				@endforeach
				@else
				<center><i>
						<p>No calendar events to show.</p>
					</i></center>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
