@extends('layouts.dashboard')

@section('title')
Training Information
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Training Information</h2>
    &nbsp;
</div>
<br>

<div class="container">
    <br><br>
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="collapsible">
                    <div class="card-header">
                        <h3>Minor Delivery & Ground</h3>
                    </div>
                </div>
                <div class="content">
                    <div class="card-body">
                        <ol>
                            @if($info_minor_gnd->count() > 0)
                                @foreach($info_minor_gnd as $i)
                                    <li>
                                        @if(Auth::user()->can('snrStaff'))
                                            <a href="/dashboard/training/info/delete/{{ $i->id }}" style="color:inherit" data-toggle="tooltip" title="Remove Information"><i class="fas fa-times"></i></a>
                                            &nbsp;
                                        @endif
                                        {{ $i->info }}
                                    </li>
                                @endforeach
                            @else
                                <p>There is no information to show.</p>
                            @endif
                            @if(Auth::user()->can('snrStaff'))
                                <br>
                                {!! Form::open(['action' => ['TrainingDash@addInfo', 0]]) !!}
                                <div class="form-row">
                                    <div class="col-sm-2">
                                        @if($info_minor_gnd->count() == 0)
                                            {!! Form::select('number', [0 => '1'], null, ['class' => 'form-control']) !!}
                                        @else
                                            {!! Form::select('number', $info_minor_gnd->first()->new_numbers, $info_minor_gnd->first()->default_new_number, ['class' => 'form-control']) !!}
                                        @endif
                                    </div>
                                    <div class="col-sm-8">
                                    {!! Form::text('info', null, ['placeholder' => 'Add Information', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Add"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            @endif
                        </ol>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="collapsible">
                    <div class="card-header">
                        <h3>Minor Local</h3>
                    </div>
                </div>
                <div class="content">
                    <div class="card-body">
                        <ol>
                            @if($info_minor_lcl->count() > 0)
                                @foreach($info_minor_lcl as $i)
                                    <li>
                                        @if(Auth::user()->can('snrStaff'))
                                            <a href="/dashboard/training/info/delete/{{ $i->id }}" style="color:inherit" data-toggle="tooltip" title="Remove Information"><i class="fas fa-times"></i></a>
                                            &nbsp;
                                        @endif
                                        {{ $i->info }}
                                    </li>
                                @endforeach
                            @else
                                <p>There is no information to show.</p>
                            @endif
                            @if(Auth::user()->can('snrStaff'))
                                <br>
                                {!! Form::open(['action' => ['TrainingDash@addInfo', 1]]) !!}
                                <div class="form-row">
                                    <div class="col-sm-2">
                                        @if($info_minor_lcl->count() == 0)
                                            {!! Form::select('number', [0 => '1'], null, ['class' => 'form-control']) !!}
                                        @else
                                            {!! Form::select('number', $info_minor_lcl->first()->new_numbers, $info_minor_lcl->first()->default_new_number, ['class' => 'form-control']) !!}
                                        @endif
                                    </div>
                                    <div class="col-sm-8">
                                    {!! Form::text('info', null, ['placeholder' => 'Add Information', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Add"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            @endif
                        </ol>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="collapsible">
                    <div class="card-header">
                        <h3>Minor Approach</h3>
                    </div>
                </div>
                <div class="content">
                    <div class="card-body">
                        <ol>
                            @if($info_minor_app->count() > 0)
                                @foreach($info_minor_app as $i)
                                    <li>
                                        @if(Auth::user()->can('snrStaff'))
                                            <a href="/dashboard/training/info/delete/{{ $i->id }}" style="color:inherit" data-toggle="tooltip" title="Remove Information"><i class="fas fa-times"></i></a>
                                            &nbsp;
                                        @endif
                                        {{ $i->info }}
                                    </li>
                                @endforeach
                            @else
                                <p>There is no information to show.</p>
                            @endif
                            @if(Auth::user()->can('snrStaff'))
                                <br>
                                {!! Form::open(['action' => ['TrainingDash@addInfo', 2]]) !!}
                                <div class="form-row">
                                    <div class="col-sm-2">
                                        @if($info_minor_app->count() == 0)
                                            {!! Form::select('number', [0 => '1'], null, ['class' => 'form-control']) !!}
                                        @else
                                            {!! Form::select('number', $info_minor_app->first()->new_numbers, $info_minor_app->first()->default_new_number, ['class' => 'form-control']) !!}
                                        @endif
                                    </div>
                                    <div class="col-sm-8">
                                    {!! Form::text('info', null, ['placeholder' => 'Add Information', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Add"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            @endif
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="collapsible">
                    <div class="card-header">
                        <h3>Major Delivery & Ground</h3>
                    </div>
                </div>
                <div class="content">
                    <div class="card-body">
                        <ol>
                            @if($info_major_gnd->count() > 0)
                                @foreach($info_major_gnd as $i)
                                    <li>
                                        @if(Auth::user()->can('snrStaff'))
                                            <a href="/dashboard/training/info/delete/{{ $i->id }}" style="color:inherit" data-toggle="tooltip" title="Remove Information"><i class="fas fa-times"></i></a>
                                            &nbsp;
                                        @endif
                                        {{ $i->info }}
                                    </li>
                                @endforeach
                            @else
                                <p>There is no information to show.</p>
                            @endif
                            @if(Auth::user()->can('snrStaff'))
                                <br>
                                {!! Form::open(['action' => ['TrainingDash@addInfo', 3]]) !!}
                                <div class="form-row">
                                    <div class="col-sm-2">
                                        @if($info_major_gnd->count() == 0)
                                            {!! Form::select('number', [0 => '1'], null, ['class' => 'form-control']) !!}
                                        @else
                                            {!! Form::select('number', $info_major_gnd->first()->new_numbers, $info_major_gnd->first()->default_new_number, ['class' => 'form-control']) !!}
                                        @endif
                                    </div>
                                    <div class="col-sm-8">
                                    {!! Form::text('info', null, ['placeholder' => 'Add Information', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Add"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            @endif
                        </ol>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="collapsible">
                    <div class="card-header">
                        <h3>Major Local</h3>
                    </div>
                </div>
                <div class="content">
                    <div class="card-body">
                        <ol>
                            @if($info_major_lcl->count() > 0)
                                @foreach($info_major_lcl as $i)
                                    <li>
                                        @if(Auth::user()->can('snrStaff'))
                                            <a href="/dashboard/training/info/delete/{{ $i->id }}" style="color:inherit" data-toggle="tooltip" title="Remove Information"><i class="fas fa-times"></i></a>
                                            &nbsp;
                                        @endif
                                        {{ $i->info }}
                                    </li>
                                @endforeach
                            @else
                                <p>There is no information to show.</p>
                            @endif
                            @if(Auth::user()->can('snrStaff'))
                                <br>
                                {!! Form::open(['action' => ['TrainingDash@addInfo', 4]]) !!}
                                <div class="form-row">
                                    <div class="col-sm-2">
                                        @if($info_major_lcl->count() == 0)
                                            {!! Form::select('number', [0 => '1'], null, ['class' => 'form-control']) !!}
                                        @else
                                            {!! Form::select('number', $info_major_lcl->first()->new_numbers, $info_major_lcl->first()->default_new_number, ['class' => 'form-control']) !!}
                                        @endif
                                    </div>
                                    <div class="col-sm-8">
                                    {!! Form::text('info', null, ['placeholder' => 'Add Information', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Add"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            @endif
                        </ol>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="collapsible">
                    <div class="card-header">
                        <h3>Major Approach</h3>
                    </div>
                </div>
                <div class="content">
                    <div class="card-body">
                        <ol>
                            @if($info_major_app->count() > 0)
                                @foreach($info_major_app as $i)
                                    <li>
                                        @if(Auth::user()->can('snrStaff'))
                                            <a href="/dashboard/training/info/delete/{{ $i->id }}" style="color:inherit" data-toggle="tooltip" title="Remove Information"><i class="fas fa-times"></i></a>
                                            &nbsp;
                                        @endif
                                        {{ $i->info }}
                                    </li>
                                @endforeach
                            @else
                                <p>There is no information to show.</p>
                            @endif
                            @if(Auth::user()->can('snrStaff'))
                                <br>
                                {!! Form::open(['action' => ['TrainingDash@addInfo', 5]]) !!}
                                <div class="form-row">
                                    <div class="col-sm-2">
                                        @if($info_major_app->count() == 0)
                                            {!! Form::select('number', [0 => '1'], null, ['class' => 'form-control']) !!}
                                        @else
                                            {!! Form::select('number', $info_major_app->first()->new_numbers, $info_major_app->first()->default_new_number, ['class' => 'form-control']) !!}
                                        @endif
                                    </div>
                                    <div class="col-sm-8">
                                    {!! Form::text('info', null, ['placeholder' => 'Add Information', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Add"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            @endif
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="card">
        <div class="collapsible">
            <div class="card-header">
                <h3>Center</h3>
            </div>
        </div>
        <div class="content">
            <div class="card-body">
                <ol>
                    @if($info_ctr->count() > 0)
                        @foreach($info_ctr as $i)
                            <li>
                                @if(Auth::user()->can('snrStaff'))
                                    <a href="/dashboard/training/info/delete/{{ $i->id }}" style="color:inherit" data-toggle="tooltip" title="Remove Information"><i class="fas fa-times"></i></a>
                                    &nbsp;
                                @endif
                                {{ $i->info }}
                            </li>
                        @endforeach
                    @else
                        <p>There is no information to show.</p>
                    @endif
                    @if(Auth::user()->can('snrStaff'))
                        <br>
                        {!! Form::open(['action' => ['TrainingDash@addInfo', 6]]) !!}
                        <div class="form-row">
                            <div class="col-sm-2">
                                @if($info_ctr->count() == 0)
                                    {!! Form::select('number', [0 => '1'], null, ['class' => 'form-control']) !!}
                                @else
                                    {!! Form::select('number', $info_ctr->first()->new_numbers, $info_ctr->first()->default_new_number, ['class' => 'form-control']) !!}
                                @endif
                            </div>
                            <div class="col-sm-8">
                            {!! Form::text('info', null, ['placeholder' => 'Add Information', 'class' => 'form-control']) !!}
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-success simple-tooltip" data-toggle="tooltip" title="Add"><i class="fas fa-check"></i></button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>

<style>
.collapsible {
cursor: pointer;
}
.content {
    overflow: hidden;
    min-height: 0;
    max-height: 0;
    transition: max-height 0.5s ease-out;
}
</style>

<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    }
  });
}
</script>

@endsection
