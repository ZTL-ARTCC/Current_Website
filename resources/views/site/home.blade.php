@extends('layouts.master')

@section('title')
Home
@endsection

@push('custom_header')
	<link rel="stylesheet" href="{{ mix('css/home.css') }}" />
@endpush

@section('content')
<div class="py-3 bg-secondary">
	<div class="container-flex rounded mb-0 mx-3" style="height: 200px; background-image:url(/photos/ZTL_Banner3.jpg); background-size:cover; background-repeat:no-repeat">
		<div class="row h-100">	
			<div class="col-* text-white h1 fw-bold d-flex align-items-center px-5">
				Atlanta Virtual ARTCC
			</div>
		</div>
	</div>
</div>
<div class="container-fluid bg-secondary">
	<div class="row">
        <div class="col-lg-2 pb-3 pb-lg-0 d-flex">
            <div class="col mt-lg-0 mt-md-3 mb-1 ms-md-1 p-2 rounded bg-dark text-white">
                <h5 class="pb-0 mb-0">Planned ATC Coverage</h5><span class="text-secondary pt-0 mt-0">ZULU Time Now: {{ Carbon\Carbon::now()->format('H:i') }}</span>
                @if(count($bookings) == 0)
                    <p class="my-3"><i class="fa fa-clock"></i> No coverage currently planned for the next 14 days. Please check back soon!</p>
                @else
                    @foreach($bookings as $date => $date_bookings)
                        <div class="my-3">
                            <strong>{{ $date }}</strong>
                            @foreach($date_bookings as $b)
                                <div class="bg-secondary px-2 py-1 mx-2 my-1 clearfix">
                                    <p class="my-0 float-start">{{ $b->callsign }}</p>
                                    <p class="my-0 float-end">{{ $b->start_time_formatted }}-{{ $b->end_time_formatted }}Z</p>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
		<div class="col-lg-6">
			<div id="eventCarousel" class="carousel slide" data-bs-ride="carousel">
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
						<img src="/photos/placeholder_banner.png" class="d-block w-100 rounded" alt="placeholder" />
					</div>
					@endif
					@if($pilot_accomplishments)
					<div class="carousel-item accomplishments">
						<a href="/pilot_passport" alt="Pilot_Passport_Challenge">
							<div class="w-100 h-100 rounded bg-dark text-white p-2">
								<h4>ZTL Pilot Passport Challenge</h4>
								<p>&nbsp;&nbsp;&nbsp;Congratulations on achieving these milestones!</p>
								<ul>
								@foreach($pilot_accomplishments as $pa)
									<li>{{ $pa->pilot_name }} - {{ $pa->challenge_name }}</li>
								@endforeach
								</ul>
							</div>
						</a>
					</div>
					@endif
				</div>
				<a class="carousel-control-prev" href="#eventCarousel" role="button" data-bs-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Previous</span>
				</a>
				<a class="carousel-control-next" href="#eventCarousel" role="button" data-bs-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Next</span>
				</a>
			</div>
		</div>
		<div class="col">
			<div class="row pe-md-2 ps-md-0">
				<div class="col mt-lg-0 mt-3 mb-1 mx-2 p-2 rounded bg-dark text-white info_card airspace_status" onclick="asxModalInstance.toggle();">
					<div class="row">
						<div class="col">
							<h4 class="pb-0 mb-0">Airspace Status</h4><span class="text-secondary pt-0 mt-0">Click for map</span>
						</div>
					</div>
					<div class="row">
						<div class="col-auto">
							<table class="table table-sm table-borderless pb-0 mb-0">
								<tr class="p-3 m-0">
									<td class="py-0 ps-1 pe-2 m-0 bg-transparent">
										@if($center === 1)
										<span class="badge bg-success">ONLINE</span>
										@else
										<span class="badge bg-danger">OFFLINE</span>
										@endif
									</td>
									<td class="py-0 px-2 m-0 text-white bg-transparent">Atlanta Center</td>
								</tr>

								@foreach ($fields as $field)
									<tr class="p-3 m-0">
										<td class="py-0 ps-1 pe-2 m-0 bg-transparent">
											@if($field['online'] === 1)
												<span class="badge bg-success">ONLINE</span>
											@else
												<span class="badge bg-danger">OFFLINE</span>
											@endif
										</td>
										<td class="py-0 px-2 m-0 text-white bg-transparent">
											{{ $field['name'] }} ATCT

											@foreach ($field['subfields'] as $subfield)
												<span class="badge {{ $subfield['color'] }}">{{ $subfield['short'] }}</span>
											@endforeach
										</td>
									</tr>

									<tr class="p-3 m-0">
										<td class="py-0 ps-1 pe-2 m-0 bg-transparent">
											@if($field['approach']['online'] === 1)
												<span class="badge bg-success">ONLINE</span>
											@else
												<span class="badge bg-danger">OFFLINE</span>
											@endif
										</td>
										<td class="py-0 px-2 m-0 text-white bg-transparent">{{ $field['name'] }} Approach</td>
									</tr>
								@endforeach

							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-auto">
							<span class="badge bg-info ms-1 me-2">{{ $overflightCount }}</span>flights in ZTL airspace
						</div>
					</div>
				</div>
			</div>
			<div class="row pe-md-2 ps-md-0">
				<div class="col my-1 mx-2 p-2 rounded bg-dark text-white info_card weather_status">
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
									<td class="py-0 ps-1 pe-2 m-0 bg-transparent">
										@if($a->visual_conditions == 'VFR')
										<span class="badge bg-success">VFR</span>
										@elseif($a->visual_conditions == 'IFR')
										<span class="badge bg-danger">IFR</span>
										@else
										<span class="badge bg-warning">{{ $a->visual_conditions }}</span>
										@endif
									</td>
									<td class="py-0 px-2 m-0 bg-transparent"><a href="/pilots/airports/view/{{ $a->id }}">{{ $a->ltr_4 }}</a></td>
									<td class="py-0 px-2 m-0 text-white bg-transparent">{{ $a->wind }}&nbsp;{{ $a->altimeter }}</td>
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
			<div class="row pe-md-2 ps-md-0">
				<div class="col my-1 mx-2 p-2 rounded bg-dark text-white info_card news_calendar" onclick="newsModalInstance.toggle();">
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
	</div>
</div>
<!-- Modals -->
<div class="modal fade" id="displayAsx" tabindex="-1" role="dialog" aria-labelledby="displayAsx" aria-hidden="true">
	<div class="modal-dialog" style="height:90vh; max-width:90vw" role="document">
		<div class="modal-content py-1 home-modal-bg">
			<div class="modal-body">
				<button type="button" class="btn-close" style="position:absolute; z-index:100; top:5px; right:15px;" data-bs-dismiss="modal" aria-label="Close"></button>
				<embed src="https://ids.ztlartcc.org/asx#dispNode" frameborder="0" style="height:85vh; width:85vw">
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="displayNewsCalendar" tabindex="-1" role="dialog" aria-labelledby="displayNews" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content py-1 home-modal-bg">
			<div class="modal-body">
				<button type="button" class="btn-close" style="position:absolute; z-index:100; top:5px; right:15px;" data-bs-dismiss="modal" aria-label="Close"></button>
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
<script src="{{mix('js/home.js')}}"></script>
@endsection
