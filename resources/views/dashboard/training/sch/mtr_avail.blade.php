@extends('layouts.dashboard')

@section('title')
@parent
| Training
@stop

@section('content')

<div class="page-heading-two">
	<div class="container">
		<h2>Training - Request Session</h2>
	</div>
</div>

@if(Auth::user()->canTrain == 0)
<div class="container">
<div class="row">
	<div class="col-sm-12">
		<center><p>Your Training Has Been Disabled By The Instructors</p></center>
	</div>
</div>
@else
<div class="container">
	<legend>
		Select an available time slot for training. Current Time: <span class="time"></span> EST
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
	
	{{ Form::open(['action' => 'TrainingController@saveSession', 'class' => 'session-request-form']) }}
	<div class="row">
			<div class="col-sm-12">
				{{Form::label('slot', 'Mentor:', ['class'=>'control-label'])}}
				{{Form::select('slot', [], 0, ['class'=>'form-control'])}}
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
				    {!! Form::label('position', 'Training Session', ['class' => 'form-label']) !!}
                    {!! Form::select('position', [
						1 => 'Minor Delivery',
                        2 => 'Major Delivery',
                        3 => 'Minor Ground',
                        4 => 'Major Ground',
                        5 => 'Minor Tower',
                        6 => 'Major Tower',
                        7 => 'Minor Approach',
                        8 => 'Major Approach',
                        9 => 'Enroute',
					], null, ['placeholder' => 'Select Training Session', 'class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					{{Form::label('date', 'Slot:', ['class'=>'control-label'])}}
					{{Form::text('date', null, ['class'=>'form-control', 'disabled' => 'disabled'])}}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					{{Form::label('comments', 'Comments:', ['class'=>'control-label'])}}
					{{Form::textarea('comments', null, ['class'=>'form-control'])}}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					{{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
				</div>
			</div>
		</div>
		
	{{ Form::close() }}
</div>

<script src="/js/moment.min.js"></script>
<script src="/js/moment-timezone-with-data-2010-2020.js"></script>
<script>
	function pad (str, max) {
		str = str.toString();
		return str.length < max ? pad("0" + str, max) : str;
	}

	var ratingIdToText = {!! json_encode(App\User::$RatingShort, JSON_FORCE_OBJECT) !!},
		currentAvailability = {!! $availability->toJSON() !!},
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

	var slotsGrouped = currentAvailability.reduce(function(pv, avail){
		if (avail.slot in pv) {
			pv[avail.slot].push(avail);
		} else {
			pv[avail.slot] = [avail];
		}

		return pv;
	}, {});

	for (var i = 0; i < 14; i++) {
		var $tr = $("<tr />");

		$tr.append($("<th />").text(now.format('MM/DD')));

		for (var j = 0; j < 24; j++) {
			var $td = $("<td />"),
				date = now.format('YYYY-MM-DD') + ' ' + pad(j, 2) + ":00:00";

			if (date in slotsGrouped) {
				$td.addClass('available');
				$td.addClass('simple-tooltip');
				$td.data('toggle', 'tooltip');

				var slots = slotsGrouped[date],
					mentors = slots.map(function(slot){
						return slot.mentor.fname + " " + slot.mentor.lname + " - " + ratingIdToText[slot.mentor.rating_id];
					});

				$td.attr('title', mentors.join("\n"));

				$td.on('click', (function(date){
					return function(){
						var slots = slotsGrouped[date],
							$form = $(".session-request-form"),
							$slot = $form.find('#slot');


						$form.show();
						$form.find('#date').val(date);

						$slot.html('');
						slots.forEach(function(slot){
							var $option = $("<option />");
							$option.val(slot.id);
							$option.text(slot.mentor.fname + " " + slot.mentor.lname + " - " + ratingIdToText[slot.mentor.rating_id]);

							$slot.append($option);
						});
					}
				})(date));
			}

			$tr.append($td);
		}

		$body.append($tr);
		now.add(1, 'd');
	}
</script>

@endif

@stop
