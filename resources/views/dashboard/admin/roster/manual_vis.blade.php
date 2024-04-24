@extends('layouts.dashboard')

@section('title')
New Visitor
@endsection

@section('content')
@include('inc.header', ['title' => 'New Visitor'])

<div class="container">
    {{ html()->form()->route('AdminDash@storeVisitor') }}
        @csrf
        @if($visitor != null)
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
                        {{ html()->text('fname', $visitor->fname)->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Last Name', 'lname') }}
                        {{ html()->text('lname', $visitor->lname)->class(['form-control']) }}
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
                        @if($visitor->facility == 'ZZN')
                            {{ html()->label('Visiting From', 'visitor_from') }}
                            {{ html()->text('visitor_from', null)->placeholder('Home ARTCC/Division')->class(['form-control']) }}
                        @else
                            {{ html()->label('Visiting From', 'visitor_from') }}
                            {{ html()->text('visitor_from', $visitor->facility)->class(['form-control']) }}
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
                        {{ html()->label('CID', 'cid') }}
                        {{ html()->text('cid', null)->placeholder('Controller CID')->class(['form-control']) }}
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
                        ], null)->placeholder(' => 'Select Rating')->class(['form-control']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('First Name', 'fname') }}
                        {{ html()->text('fname', null)->placeholder('First Name')->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Last Name', 'lname') }}
                        {{ html()->text('lname', null)->placeholder('Last Name')->class(['form-control']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('Email', 'email') }}
                        {{ html()->text('email', null)->placeholder('Email')->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        {{ html()->label('Initials', 'initials') }}
                        {{ html()->text('initials', null)->placeholder('Initials')->class(['form-control']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {{ html()->label('Visiting From', 'visitor_from') }}
                        {{ html()->text('visitor_from', null)->placeholder('Home ARTCC/Division')->class(['form-control']) }}
                    </div>
                </div>
            </div>
        @endif
        <br>
        <div class="form-group inline">
            <button class="btn btn-success" type="submit">Save</button>
            <a class="btn btn-danger" href="/dashboard/admin/roster/visit/requests">Cancel</a>
        </div>
    {{ html()->form()->close() }}
        </div>
</div>
@endsection
