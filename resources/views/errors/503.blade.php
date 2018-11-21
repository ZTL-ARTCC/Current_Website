@extends('layouts.master')

@section('title')
Under Maintenance
@endsection

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Maintenance in Progress</h2>
        &nbsp;
    </div>
</span>
<br>

<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Whoops there is currently website maintenance in progress! We will be back online in a few...</h3>
                </div>
                <div class="col-sm-4">
                    <img src="/photos/construction.gif" width="300px">
                    <p><small>Let's hope this isn't happening!</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
