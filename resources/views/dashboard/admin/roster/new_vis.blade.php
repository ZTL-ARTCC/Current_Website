@extends('layouts.dashboard')

@section('title')
New Visitor
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>New Visitor</h2>
    &nbsp;
</div>
<br>
<div class="container">
    {!! Form::open(['action' => 'AdminDash@storeVisitor']) !!}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('cid', 'CID') !!}
                    {!! Form::text('cid', $visitor->cid, ['class' => 'form-control', 'disabled']) !!}
                    {!! Form::hidden('cid', $visitor->cid) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('rating_id', 'Rating') !!}
                    {!! Form::select('rating_id', [
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
                    ], $visitor->rating, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('fname', 'First Name') !!}
                    {!! Form::text('fname', $fname, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('lname', 'Last Name') !!}
                    {!! Form::text('lname', $lname, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::text('email', $visitor->email, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('initials', 'Initials') !!}
                    {!! Form::text('initials', $initials, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('visitor_from', 'Visiting From') !!}
                    {!! Form::text('visitor_from', $visitor->home, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
		@if ($user != false)
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12">
					<p>Note: a record matching this user's CID was found in the facility database. This usually occurs when a user has been a previous member of the facility and then attempts to re-join. Do you want to grant this user their previous certifications?</p>
					{!! Form::label('grant_previous', 'Grant Previous Certifications?') !!}
					{!! Form::checkbox('grant_previous', 1, true) !!}
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
    {!! Form::close() !!}
        </div>
</div>
@endsection
