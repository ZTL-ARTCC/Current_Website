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
        <br>
        <div class="row">
            <div class="col-sm-1">
                <button class="btn btn-success" type="submit">Save</button>
            </div>
    {!! Form::close() !!}
        </div>
</div>
@endsection
