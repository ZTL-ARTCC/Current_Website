@extends('layouts.dashboard')

@section('title')
New Visitor
@endsection

@section('content')
@include('inc.header', ['title' => 'New Visitor'])

<div class="container">
    {{ html()->form()->route('storeVisitor') }}
        @csrf
        @if($visitor != null)
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="cid">CID</label>
                        {{ html()->text('cid', $visitor->cid)->class(['form-control'])->attributes(['disabled']) }}
                        {{ html()->hidden('cid', $visitor->cid) }}
                    </div>
                    <div class="col-sm-6">
                        <label for="rating_id">Rating</label>
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
                        <label for="fname">First Name</label>
                        {{ html()->text('fname', $visitor->fname)->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        <label for="lname">Last Name</label>
                        {{ html()->text('lname', $visitor->lname)->class(['form-control']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="email">Email</label>
                        {{ html()->text('email', $visitor->email)->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        <label for="initials">Initials</label>
                        {{ html()->text('initials', $initials)->class(['form-control']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        @if($visitor->facility == 'ZZN')
                            <label for="visitor_from">Visiting From</label>
                            {{ html()->text('visitor_from', null)->placeholder('Home ARTCC/Division')->class(['form-control']) }}
                        @else
                            <label for="visitor_from">Visiting From</label>
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
                        <label for="cid">CID</label>
                        {{ html()->text('cid', null)->placeholder('Controller CID')->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        <label for="rating_id">Rating</label>
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
                        <label for="fname">First Name</label>
                        {{ html()->text('fname', null)->placeholder('First Name')->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        <label for="lname">Last Name</label>
                        {{ html()->text('lname', null)->placeholder('Last Name')->class(['form-control']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="email">Email</label>
                        {{ html()->text('email', null)->placeholder('Email')->class(['form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        <label for="initials">Initials</label>
                        {{ html()->text('initials', null)->placeholder('Initials')->class(['form-control']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="visitor_from">Visiting From</label>
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
