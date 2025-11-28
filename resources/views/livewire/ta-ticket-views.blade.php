<div>
    <div class="row bg-warning w-100 my-2 rounded" wire:loading>
        <h3 class="text-center p-2"><i class="fa-solid fa-hourglass-start me-2"></i> Refreshing Dataset... please wait</h3>
    </div>
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="card mb-4">
                <div class="card-header text-start">
                    <i class="fa-solid fa-filter me-2"></i>Filter Tickets
                </div>
                <div class="card-body">
                    <div class="row-mb-3">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="student" class="form-label">Student</label>
                                {{ html()->select('student', $students, null)->placeholder('Select Student')->class(['form-select'])->attribute('wire:model.change', 'student') }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="trainer" class="form-label">Trainer</label>
                                {{ html()->select('trainer', $trainers, null)->placeholder('Select Trainer')->class(['form-select'])->attribute('wire:model.change', 'trainer') }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="start_date" class="form-label">Start Date</label>
                                <div class="input-group date dt_picker_date" id="datetimepicker1" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                    {{ html()->text('start_date', null)->placeholder('MM/DD/YYYY')->class(['form-control','datetimepicker-input'])->id('startDate')->attributes(['data-td-target' => '#datetimepicker1']) }}
                                    <span class="input-group-text" data-td-target="#datetimepicker1" data-td-toggle="datetimepicker">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="end_date" class="form-label">End Date</label>
                                <div class="input-group date dt_picker_date" id="datetimepicker2" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                    {{ html()->text('end_date', null)->placeholder('MM/DD/YYYY')->class(['form-control','datetimepicker-input'])->id('endDate')->attributes(['data-td-target' => '#datetimepicker2']) }}
                                    <span class="input-group-text" data-td-target="#datetimepicker2" data-td-toggle="datetimepicker">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($tickets)
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">View</th>
                            <th scope="col">Training Date</th>
                            <th scope="col">Trainer Name</th>
                            <th scope="col">Position</th>
                            <th scope="col">Session Type</th>
                            <th scope="col">Session ID</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">End Time</th>
                            <th scope="col">Score<br>(1-5)</th>
                            <th scope="col">Movements</th>
                            <th scope="col">INS/MTR Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php($transition_date = \Carbon\Carbon::parse('11/12/2021')) {{-- ticket dates eastern timezone after this date --}}
                    @foreach($tickets as $t)
                        @if($t->cert) {{-- student certified: green highlight --}}
                            <tr class="table-success" wire:key="{{ $t->id }}">
                        @elseif($t->monitor) {{-- student may be monitored: blue highlight --}}
                            <tr class="table-primary" wire:key="{{ $t->id }}">
                        @else
                            <tr wire:key="{{ $t->id }}">
                        @endif
                        <td>
                            <a href="/dashboard/training/tickets/view/{{ $t->id }}" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                        <td>{{ $t->date }}</td>
                        <td>{{ $t->trainer_name }}</td>
                        <td>{{ $t->position_name }}</td>
                        <td>{{ $t->type_name }}</td>
                        <td>{{ $t->session_name }}</td>
                        <td>{{ $t->start_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                        <td>{{ $t->end_time }}@if(\Carbon\Carbon::parse($t->date)->lt($transition_date)) Z @else ET @endif</td>
                        <td>@if($t->score) {{ $t->score }} @else N/A @endif</p>
	                    <td>@if($t->movements) {{ $t->movements }} @else N/A @endif</td>
                        <td data-bs-toggle="tooltip" data-bs-html="true" title="{{ $t->ins_comments }}">{!! str_limit(strip_tags($t->ins_comments, '<p>'), 40, '...') !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                @include('inc.empty_state', ['header' => 'No Training Tickets', 'body' => 'There are no training tickets for the selected filter categories.', 'icon' => 'fa-solid fa-file'])
            @endif
        </div>
    </div>
</div>
@script
<script>
    const dtPickers = document.querySelectorAll('.datetimepicker-input');
    dtPickers.forEach(element => {
        element.addEventListener('change', function() {
            $wire.dispatch('update-dates', {field: element.id, value: element.value});
        });
    });
</script>
@endscript
