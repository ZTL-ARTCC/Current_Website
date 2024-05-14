@extends('layouts.master')

@section('title')
Visit
@endsection

@section('content')
@include('inc.header', ['title' => 'Visit ZTL ARTCC', 'type' => 'external'])

<div class="d-flex justify-content-center mb-5">
    <div class="position-unset col-xl-6 col-10">
        <div class="fade position-unset m-0 undefined alert alert-primary show" role="alert">
            <table>
                <tbody>
                    <tr>
                        <td class="pb-2 pb-md-1">&nbsp;</td>
                        <td class="pb-2 pb-md-1">
                            <h5 class="mb-0">Visiting Prerequisites:</h5>
                        </td>
                    </tr>
                    <tr>
                        <td class="d-none d-md-table">&nbsp;</td>
                        <td colspan="2">
                            <li class="m-0">You must have a home facility.</li>
                            <li class="m-0">Prospective visitors are required to consolidate their current rating by performing at least 50 controlling hours at their current rating in the ARTCC where the rating was granted prior to visiting.</li>
                            <li class="m-0">Prospective visitors must wait at least 90 days after any rating change (promotion) before visiting a new ARTCC.</li>
                            <li class="m-0">Prospective visitors must hold at least an S3 rating.</li>
                            <li class="m-0">Prospective visitors must wait at least 60 days after joining a visiting roster before visiting a new ARTCC.</li>
                            <li class="m-0">See <a href="https://vatusa-storage.nyc3.cdn.digitaloceanspaces.com/docs/general-division-policy.pdf" alt="DP001">DP001 - VATUSA General Division Policy</a> for more information</li>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="container">
    {!! Form::open(['action' => 'FrontController@storeVisit']) !!}
    <div class="form-group">
        <div class="row">
            <div class="col-sm-6">
                {!! Form::label('cid', 'CID') !!}
                {!! Form::text('cid', null, ['placeholder' => 'Required', 'class' => 'form-control']) !!}
            </div>
            <div class="col-sm-6">
                {!! Form::label('name', 'Full Name') !!}
                {!! Form::text('name', null, ['placeholder' => 'Required', 'class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-6">
                {!! Form::label('email', 'Email') !!}
                {!! Form::email('email', null, ['placeholder' => 'Required', 'class' => 'form-control']) !!}
            </div>
            <div class="col-sm-3">
                {!! Form::label('rating', 'Rating') !!}
                {!! Form::select('rating', [
                1 => 'Observer (OBS)', 2 => 'Student 1 (S1)',
                3 => 'Student 2 (S2)', 4 => 'Senior Student (S3)',
                5 => 'Controller (C1)', 7 => 'Senior Controller (C3)',
                8 => 'Instructor (I1)', 10 => 'Senior Instructor (I3)'
                ], null, ['placeholder' => 'Select Rating', 'class' => 'form-control']) !!}
            </div>
            <div class="col-sm-3">
                {!! Form::label('home', 'Home ARTCC') !!}
                {!! Form::text('home', null, ['placeholder' => 'Required', 'class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('reason', 'Explanation of Why You Want to Visit the ZTL ARTCC') !!}
        {!! Form::textArea('reason', null, ['placeholder' => 'Required', 'class' => 'form-control']) !!}
    </div>
    <div class="g-recaptcha" data-sitekey="{{ config('google.site_key') }}"></div>
    <br>
    <button class="btn btn-success" type="submit">Submit</button>
    {!! Form::close() !!}
</div>
@endsection
