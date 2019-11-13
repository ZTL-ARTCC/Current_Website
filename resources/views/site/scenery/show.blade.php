@extends('layouts.master')

@section('title')
View Scenery
@endsection

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Scenery for {{ $scenery->airport }}</h2>
        &nbsp;
    </div>
</span>
<br>

<div class="container">
    <a href="/pilots/scenery" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
    <br><br>

    <div class="card">
        <div class="card-header">
            <h3>
                Pictures of scenery for <a href="{{ $scenery->link }}" target="_blank">{{ $scenery->airport }} by {{ $scenery->developer }}</a> for 
                @if($scenery->sim == 0) 
                    FSX and P3D 
                @else 
                    X-Plane 
                @endif
				@if($scenery->price == 0)
					(Free!)
				@else
					({{ $scenery->price }} {{ $scenery->currency }})
				@endif
            </h3>
        </div>
        <div class="card-body">
            @if($scenery->image1 && $scenery->image2 && $scenery->image3)
				<div align="center">
					<img src="{{ $scenery->image1 }}" width="600px">
					<br><br>
					<img src="{{ $scenery->image2 }}" width="600px">
					<br><br>
					<img src="{{ $scenery->image3 }}" width="600px">
				</div>
            @elseif($scenery->image1 && $scenery->image2)
				<div align="center">
					<img src="{{ $scenery->image1 }}" width="600px">
					<br><br>
					<img src="{{ $scenery->image2 }}" width="600px">
				</div>
            @elseif($scenery->image1)
				<div align="center">
					<img src="{{ $scenery->image1 }}" width="600px">
				</div>
			@else
                <center><h3><i>No pictures for this scenery.</i></h3></center>
            @endif
        </div>
    </div>

@endsection