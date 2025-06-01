@extends('layouts.dashboard')

@section('title')
New Store Item
@endsection

@section('content')
@include('inc.header', ['title' => 'New Store Item'])

<div class="container">
    {{ html()->form()->route('saveNewStoreItem')->attribute('enctype', 'multipart/form-data')->open() }}
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="apt">Title</label>
                    {{ html()->text('title', null)->class(['form-control'])->placeholder('Required') }}
                </div>
                <div class="col-sm-6">
                    <label for="sim">Flags</label>
                    {{ html()->select('flag', [
                        '' => 'None',
                        'coming soon' => 'Coming Soon',
                        'limited stock' => 'Limited Stock',
                        'out of stock' => 'Out of Stock',
                        'new' => 'New'
                    ], null)->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12">
                    <label for="price">Description</label>
                    {{ html()->textarea('description', null)->class(['form-control'])->placeholder('Required') }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label for="price">Price</label>
                    {{ html()->text('price', null)->class(['form-control'])->placeholder('Digits only ex 1.99 (Required)') }}
                </div>
                <div class="col-sm-6">
                    <label for="currency">Image</label>
                    {{ html()->file('image', null)->class(['form-control']) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1">
                <button class="btn btn-success" type="submit">Submit</button>
            </div>
    {{ html()->form()->close() }}
            <div class="col-sm-1">
                <a href="/dashboard/admin/store" class="btn btn-danger">Cancel</a>
            </div>
        </div>
</div>
@endsection
