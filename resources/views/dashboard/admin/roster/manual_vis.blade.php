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
        @if($visitor != null)
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
                        {!! Form::text('fname', $visitor->fname, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('lname', 'Last Name') !!}
                        {!! Form::text('lname', $visitor->lname, ['class' => 'form-control']) !!}
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
                        @if($visitor->facility == 'ZZN')
                            {!! Form::label('visitor_from', 'Visiting From') !!}
                            {!! Form::text('visitor_from', null, ['placeholder' => 'Home ARTCC/Division', 'class' => 'form-control']) !!}
                        @else
                            {!! Form::label('visitor_from', 'Visiting From') !!}
                            {!! Form::text('visitor_from', $visitor->facility, ['class' => 'form-control']) !!}
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-danger">
                No controller was found with that CID so please enter their information manually.
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('cid', 'CID') !!}
                        {!! Form::text('cid', null, ['placeholder' => 'Controller CID', 'class' => 'form-control']) !!}
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
                        ], null, ['placeholder' => 'Select Rating', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('fname', 'First Name') !!}
                        {!! Form::text('fname', null, ['placeholder' => 'First Name', 'class' => 'form-control']) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('lname', 'Last Name') !!}
                        {!! Form::text('lname', null, ['placeholder' => 'Last Name', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('initials', 'Initials') !!}
                        {!! Form::text('initials', null, ['placeholder' => 'Initials', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('visitor_from', 'Visiting From') !!}
                        {!! Form::text('visitor_from', null, ['placeholder' => 'Home ARTCC/Division', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        @endif
        <br>
        <div class="form-group inline">
            <button class="btn btn-success" type="submit">Save</button>
            <a class="btn btn-danger" href="/dashboard/admin/roster/visit/requests">Cancel</a>
        </div>
    {!! Form::close() !!}
        </div>
</div>
@endsection
