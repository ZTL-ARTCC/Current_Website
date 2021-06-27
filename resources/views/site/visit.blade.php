@extends('layouts.master')

@section('title')
Visit
@endsection

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Visit ZTL ARTCC</h2>
        &nbsp;
    </div>
</span>
<br>
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
  <li class="m-0">You must have 50 hours logged on your current rating.</li>
  <li class="m-0">You must have been a member of your current facility for 90 days.</li>
  <li class="m-0">You must have received your most recent VATSIM rating greater than 90 days ago.</li>
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
        <div class="g-recaptcha" data-sitekey="6LcC3XoUAAAAAG8ST6HXqS3_reIZRLcA09sDdodw"></div>
        <br>
        <button class="btn btn-success" type="submit">Submit</button>
    {!! Form::close() !!}
</div>
@endsection
