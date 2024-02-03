@extends('layouts.dashboard')

@section('title')
Feature Toggles
@endsection

@section('content')
<div class="container-fluid view-header">
    <h2>Feature Toggles</h2>
</div>
<br>
<div class="container">
    <a href="/dashboard/admin/toggles/create" class="btn btn-primary mb-4">New Toggle</a>
    <table class="table table-outline">
        <thead>
            <tr>
                <th scope="col text-center">Toggle Name</th>
                <th scope="col text-center">Toggle Description</th>
                <th scope="col text-center">Flip</th>
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
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="/dashboard/admin/toggles/toggle/{{ $t->toggle_name }}" class="btn btn-primary simple-tooltip" data-toggle="tooltip" title="Flip Toggle"><i class="fas fa-sliders-h fa-fw"></i></a>
                            <a href="/dashboard/admin/toggles/edit/{{ $t->toggle_name }}" class="btn btn-warning simple-tooltip" data-toggle="tooltip" title="Edit Toggle"><i class="fas fa-pencil-alt fa-fw"></i></i></a>
                            <a href="/dashboard/admin/toggles/delete/{{ $t->toggle_name }}" class="btn btn-danger simple-tooltip" data-toggle="tooltip" title="Delete Toggle"><i class="fas fa-trash fa-fw"></i></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
