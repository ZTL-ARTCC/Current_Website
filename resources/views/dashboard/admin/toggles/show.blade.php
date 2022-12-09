@extends('layouts.dashboard')

@section('title')
Feature Toggles
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Feature Toggles</h2>
    &nbsp;
</div>
<br>
<div class="container">
    <a href="/dashboard/admin/toggles/create" class="btn btn-primary mb-4">New Toggle</a>
    <table class="table table-outline">
        <thead>
            <tr>
                <th scope="col"><center>Toggle Name</center></th>
                <th scope="col"><center>Toggle Description</center></th>
                <th scope="col"><center>Flip</center></th>
            </tr>
        </thead>
        <tbody>
            @foreach($toggles as $t)
                <tr>
                    <td>
                        {{ $t->toggle_name }}
                        @if($t->is_enabled)
                            <span class="badge badge-success">Enabled</span>
                        @else
                            <span class="badge badge-dark">Disabled</span>
                        @endif
                    </td>
                    <td>{{ $t->toggle_description }}</td>
                    <td>
                        <center>
                            <a href="/dashboard/admin/toggles/toggle/{{ $t->toggle_name }}" class="btn btn-primary simple-tooltip" data-toggle="tooltip" title="Flip Toggle"><i class="fas fa-sliders-h"></i></a>
                        </center>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
