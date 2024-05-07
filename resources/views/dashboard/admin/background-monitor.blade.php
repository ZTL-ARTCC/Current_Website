@extends('layouts.dashboard')

@section('title')
Background Task Monitor
@endsection

@section('content')
@include('inc.header', ['title' => 'Background Task Monitor'])

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th colspan="2">Task</th>
            <th>Schedule</th>
            <th>Last Run Started</th>
            <th>Last Run Finished</th>
            <th>Last Failed Time</th>
            <th>Next Run</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
            <tr>
                @if ($task->lastRunFinishedTooLate() && $task->lastRunFinishedAt() || $task->lastRunFailed())
                    <td class="bg-danger text-center"><i class="fas fa-times"></i></td>
                @else
                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                @endif
                    <td class="simple-tooltip" role="button" data-toggle="tooltip" title="{{ $task->name() }}" onclick="copyTextToClipboard('{{ $task->defaultName() }}')">
                        {{ $task->defaultName() }}
                        <span class="float-right"><i class="fas fa-clipboard"></i></span>
                    </td>
                <td>{{ $task->humanReadableCron() }}</td>
                <td>{{ optional($task->lastRunStartedAt())->format($format) ?? '' }}</td>
                <td>{{ optional($task->lastRunFinishedAt())->format($format) ?? '' }}</td>
                <td>{{ optional($task->lastRunFailedAt())->format($format) ?? '' }}</td>
                <td>{{ $task->nextRunAt()->format($format) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{Html::script(asset('js/copy_to_clipboard.js'))}}
@endsection
