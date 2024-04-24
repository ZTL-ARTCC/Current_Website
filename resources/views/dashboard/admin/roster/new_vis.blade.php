@extends('layouts.dashboard')

@section('title')
New Visitor
@endsection

@section('content')
@include('inc.header', ['title' => 'New Visitor'])

<div class="container">
    {{ html()->form()->route('AdminDash@storeVisitor') }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {{ html()->label('CID', 'cid') }}
                    {{ html()->text('cid', $visitor->cid)->class(['form-control'])->attributes(['disabled']) }}
                    {{ html()->hidden('cid', $visitor->cid) }}
                </div>
                <div class="col-sm-6">
                    {{ html()->label('Rating', 'rating_id') }}
                    {{ html()->select('rating_id', [
                        0 => 'Pilot',
                        1 => 'Observer (OBS)',
                        2 => 'Student 1 (S1)',
                        3 => 'Student 2 (S2)',
                        4 => 'Senior Student (S3)',
                        5 => 'Controller (C1)',
                        7 => 'Senior Controller (C3)',
                        8 => 'Instructor (I1)',
                        10 => 'Senior Instructor (I3)',
                        11 => 'Supervisor (SUP)',
                        12 => 'Admin (ADM)',
                    ], $visitor->rating)->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {{ html()->label('First Name', 'fname') }}
                    {{ html()->text('fname', $fname)->class(['form-control']) }}
                </div>
                <div class="col-sm-6">
                    {{ html()->label('Last Name', 'lname') }}
                    {{ html()->text('lname', $lname)->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {{ html()->label('Email', 'email') }}
                    {{ html()->text('email', $visitor->email)->class(['form-control']) }}
                </div>
                <div class="col-sm-6">
                    {{ html()->label('Initials', 'initials') }}
                    {{ html()->text('initials', $initials)->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {{ html()->label('Visiting From', 'visitor_from') }}
                    {{ html()->text('visitor_from', $visitor->home)->class(['form-control']) }}
                </div>
            </div>
        </div>
		@if ($user != false)
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12">
					<p>Note: a record matching this user's CID was found in the facility database. This usually occurs when a user has been a previous member of the facility and then attempts to re-join. Do you want to grant this user their previous certifications?</p>
					{{ html()->label('Grant Previous Certifications?', 'grant_previous') }}
					{{ html()->checkbox('grant_previous', true, 1) }}
				</div>
			</div>
			<div class="row">
                <div class="col-sm-12">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col"><center>Status</center></th>
                        <th scope="col"><center>Delivery</center></th>
                        <th scope="col"><center>Ground</center></th>
                        <th scope="col"><center>Tower</center></th>
                        <th scope="col"><center>Approach</center></th>
                        <th scope="col"><center>Center</center></th>
                    </tr>
                </thead>
                <tbody>
                    @php
						$c = $user;
					@endphp
                        <tr>
                            <td>
                                {{ $c->backwards_name }}
                            </td>
                            <td><center>{{ $c->status_text }}</center></td>
                            @if($c->del == 0)
                                <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                            @elseif($c->del == 1)
                                <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                            @elseif($c->del == 2)
                                <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                            @endif
                            @if($c->gnd == 0)
                                <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                            @elseif($c->gnd == 1)
                                <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                            @elseif($c->gnd == 2)
                                <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                            @endif
                            @if($c->twr == 0)
                                <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                            @elseif($c->twr == 1)
                                <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                            @elseif($c->twr == 2)
                                <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                            @elseif($c->twr == 99)
                                <td><center><i class="fab fa-stripe-s" data-toggle="tooltip" style="color:#c1ad13" title="Expires: {{ $c->solo }}"></i></center></td>
                            @endif
                            @if($c->app == 0)
                                <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                            @elseif($c->app == 1)
                                <td><center><i class="far fa-check-circle" style="color:green"></i></center></td>
                            @elseif($c->app == 2)
                                <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                            @elseif($c->app == 90)
                                <td><center><b style="color:blue;">A80 SAT</b></center></td>
                            @elseif($c->app == 91)
                                <td><center><b style="color:blue;">A80 DR</b></center></td>
                            @elseif($c->app == 92)
                                <td><center><b style="color:blue;">A80 TAR</b></center></td>    
                            @elseif($c->app == 99)
                                <td><center><i class="fab fa-stripe-s" data-toggle="tooltip" style="color:#c1ad13" title="Expires: {{ $c->solo }}"></i></center></td>
                            @elseif($c->app == 89)
                                <td><center style="color:#c1ad13">M</center></td>
                            @endif
                            @if($c->ctr == 0)
                                <td><center><i class="fas fa-times" style="color:red"></i></center></td>
                            @elseif($c->ctr == 1)
                                <td><center><i class="fas fa-check" style="color:green"></i></center></td>
                            @elseif($c->ctr == 99)
                                <td><center><i class="fab fa-stripe-s" data-toggle="tooltip" style="color:#c1ad13" title="Expires: {{ $c->solo }}"></i></center></td>
                            @endif
                        </tr>
                </tbody>
            </table>					
                </div>
            </div>
        </div>			
		@endif
        <br>
        <div class="row">
            <div class="col-sm-1">
                <button class="btn btn-success" type="submit">Save</button>
            </div>
    {{ html()->form()->close() }}
        </div>
</div>
@endsection
