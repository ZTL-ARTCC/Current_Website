<div class="text-start" wire:poll.60s>
    @if ($ots_solo_str != '')
    <div class="row text-center mx-1">
        <div class="col-sm-12 bg-info rounded">
            {!! $ots_solo_str !!}
        </div>
    </div>
    @endif
    <div class="row mt-4">
        <div class="col-sm-3">
            <div class="card h-100">
                <div class="card-header">
                    <b>{{ $user->full_name }}</b> ({{ $user->id }})
                    <h5 class="float-end">{!! $rating_badge !!}</h5>
                </div>
                <div class="card-body text-center">
                    {!! $avatar !!}
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fa-solid fa-circle-info me-2"></i>
                    Student Information
                </div>
                <div class="card-body">
                    <div>Controller rating: <strong>{{ $user->rating_long }}</strong></div>
                    <div>ZTL {!! $home_status !!}</div>
                    <div>Last controller activity: <strong>{{ $last_control_str }}</strong></div>
                    <div>Date of last promotion: <strong>{{ $user->last_promotion_date }}</strong></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-list-check me-2"></i>VATUSA Academy & ZACK Course Status
                </div>
                <div class="card-body text-white">
                    @foreach ($examTypes as $examType)
                            @isset($exams[$examType])
                                @if($exams[$examType]['success'] == 1)
                                    <span class="badge bg-success"><img src="{{ Vite::image('vatusa.png') }}" class="mr-2" alt="VATUSA"><strong>{{ $examType }}:</strong> {{ $exams[$examType]['date'] }} ({{ $exams[$examType]['grade'] }}%)</span>
                                @elseif($exams[$examType]['success'] == 0)
                                    <span class="badge bg-danger"><img src="{{ Vite::image('vatusa.png') }}" class="mr-2" alt="VATUSA"><strong>{{ $examType }}:</strong> {{ $exams[$examType]['date'] }} ({{ $exams[$examType]['grade'] }}%)</span>
                                @else
                                    <span class="badge bg-secondary"><img src="{{ Vite::image('vatusa.png') }}" class="mr-2" alt="VATUSA"><strong>{{ $examType }}:</strong> No date</span>
                                @endif
                            @endisset
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @if($student_note)
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-comment me-2"></i>TA Notes
                    @if(Auth::user()->hasRole('ata') || Auth::user()->isAbleTo('snrStaff'))
                        <a href="/dashboard/training/statistics#studnotes" class="float-end" target="_blank"><i class="fa-solid fa-pen-to-square"></i></a>
                    @endif
                </div>
                <div class="card-body">
                    {!! $student_note->note !!}
                    <br>
                    <small>Last modified by {{ $student_note->entered_by_name }} ({{ $student_note->last_modified }})</small>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-rectangle-list me-2"></i>Recent activity log
                </div>
                <div class="card-body">
                @if (count($controller_activity) > 0)
                <div class="table">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col">Date/time</th>
                                <th scope="col">Position</th>
                                <th scope="col">Duration (hrs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($controller_activity as $control_session)
                                <tr>
                                    <td scope="col">{{ $control_session->created_at->format('F j, Y H:i:s') }}</td>
                                    <td scope="col">{{ $control_session->position }}</td>
                                    <td scope="col">{{ $control_session->created_at->diffInHours($control_session->updated_at) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    @include('inc.empty_state', ['header' => 'No Activity Logged', 'body' => 'No ZTL controller activity found.', 'icon' => 'fa-solid fa-face-frown'])
                @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-calendar me-2"></i>Training sessions scheduled
                    <a href="{{ config('scheddy.base') }}" class="float-end" target="_blank"><i class="fa-solid fa-link"></i></a>
                </div>
                <div class="card-body">
            @if($appointments_successful && count($appointments) > 0)
                <div class="table">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col">Date/time</th>
                                <th scope="col">Lesson Type</th>
                                <th scope="col">Instructor/Mentor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td scope="col">{{ \Carbon\Carbon::parse($appointment->session->start)->setTimezone(Auth::User()->timezone)->format('m/d/y g:i A') }} {{ Auth::User()->timezone_abbr }}</td>
                                    <td scope="col">{{ $appointment->sessionType->name }}</td>
                                    <td scope="col">{{ $appointment->mentor->firstName }} {{ $appointment->mentor->lastName }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif(! $appointments_successful)
                @include('inc.empty_state', ['header' => 'No Training Appointments', 'body' => 'Could not load training appointments.', 'icon' => 'fa-solid fa-warning', 'body_class' => 'text-danger'])
            @else
                @include('inc.empty_state', ['header' => 'No Training Appointments', 'body' => 'No training appointments found.', 'icon' => 'fa-solid fa-calendar'])
            @endif

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-ticket-simple me-2"></i>Training Tickets
                    <select class="form-select form-select-sm float-end w-25 py-0" wire:model.change='ticket_select'>
                        <option value="s1" selected>S1</option>
                        <option value="s2">S2</option>
                        <option value="s3">S3</option>
                        <option value="c1">C1</option>
                        <option value="other">Other</option>
                        <option value="drafts">Drafts</option>
                    </select>
                </div>
                <div class="card-body">
               @if (count($tickets) > 0)
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
                                    <tr class="table-success">
                                @elseif($t->monitor) {{-- student may be monitored: blue highlight --}}
                                    <tr class="table-primary">
                                @else
                                    <tr>
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
                                @if($t->controller_id == Auth::id() && Auth::user()->hasRole('mtr'))
                                    <td data-bs-toggle="tooltip" title="Not Authorized">Not Authorzized</td>
                                @else
                                    <td data-bs-toggle="tooltip" data-bs-html="true" title="{{ $t->ins_comments }}">{!! str_limit(strip_tags($t->ins_comments, '<p>'), 40, '...') !!}</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    @include('inc.empty_state', ['header' => 'No Tickets', 'body' => 'No tickets found in the ' . ucfirst($ticket_select) . ' category.', 'icon' => 'fa-solid fa-face-frown'])
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
