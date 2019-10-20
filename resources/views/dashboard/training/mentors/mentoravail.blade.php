@extends('layouts.dashboard')

@section('title')
@parent
| Training
@stop

@section('content')

<div class="page-heading-two">
	<div class="container">
		<h2>Admin - Training - Availability</h2>
	</div>
</div>

<div class="container">
	<legend>
		Select time blocks available for mentoring. Current Time: <span class="time"></span> EST
	</legend>

	<div class="table-responsive">
		<table class="availability table table-bordered table-condensed">
			<thead>
				<tr></tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	<p class="text-right">
		<button class="save-btn btn btn-primary">Save</button>
	</p>


	{{ Form::open(['action' => 'MentorController@postAvail', 'class' => 'avail-form']) }}
	{{ Form::close() }}
</div>

<script src="/js/moment.min.js"></script>
<script src="/js/moment-timezone-with-data-2010-2020.js"></script>
<script>
	function pad (str, max) {
		str = str.toString();
		return str.length < max ? pad("0" + str, max) : str;
	}

	var currentAvailability = {!!  $availability->toJSON() !!},
		now = moment(),
		$table = $(".availability"),
		$headerRow = $table.find('thead tr'),
		$body = $table.find('tbody');

	now.tz('America/New_York');

	$(".time").text(now.format("HH:mm"));

	$headerRow.append($("<th />").text("Date"));

	for (var i = 0; i < 24; i++) {
		$headerRow.append($("<th />").text(pad(i, 2) + ":00"));
	}

	for (var i = 0; i < 14; i++) {
		var $tr = $("<tr />");

		$tr.append($("<th />").text(now.format('MM/DD')));

		for (var j = 0; j < 24; j++) {
			var $td = $("<td />");
			$td.data('date', now.format('YYYY-MM-DD') + ' ' + pad(j, 2) + ":00:00");
			$tr.append($td);
		}

		$body.append($tr);
		now.add(1, 'd');
	}

	var mousedown = false,
		availabilityStart = null;

	$body.find('td').on('mousedown', function(evt){
		if (evt.button != 0) return;

		var $target = $(evt.target);

		availabilityStart = !$target.hasClass('available');

		$target.toggleClass('available');
		mousedown = true;
	});

	$body.find('td').on('mouseenter', function(evt){
		var $target = $(evt.target);

		if (mousedown == true) {
			if (availabilityStart) {
				$target.addClass('available');	
			} else {
				$target.removeClass('available');
			}
		}
	});

	$(document).on('mouseup', function(){
		mousedown = false;
		availableStart = null;
	});

	$(".save-btn").on('click', function(){
		var $availableSlots = $body.find('td.available');

		$availableSlots.each(function(i, el){
			var $input = $("<input />");
			$input.attr('type', 'hidden');
			$input.attr('name', 'slots[]');
			$input.val($(el).data('date'))

			$(".avail-form").append($input);
		});

		$(".avail-form").submit();
	});

	var currentSlots = currentAvailability.map(function(avail){
		return avail.slot;
	});

	$body.find('td').each(function(i, el){
		var $el = $(el);
		if (currentSlots.indexOf($el.data('date')) > -1) {
			$el.addClass('available');	
		}
	});


</script>

@stop
