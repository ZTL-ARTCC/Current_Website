@extends('layouts.dashboard')

@section('title')
Feedback Management
@endsection

@section('content')
@include('inc.header', ['title' => 'Feedback Management'])

<div class="container">
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link tab-link active" href="#new" role="tab" data-bs-toggle="tab">New Feedback</a>
        </li>
        <li class="nav-item">
            <a class="nav-link tab-link" href="#processed" role="tab" data-bs-toggle="tab">Processed Feedback</a>
        </li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="new">
            @if($feedback->count() > 0)
                <table class="table table-outline">
                    <thead>
                        <tr>
                            <th scope="col">Position</th>
                            <th scope="col">Controller/Event</th>
                            <th scope="col">Pilot</th>
                            <th scope="col">Comments</th>
                            <th scope="col">Submitted</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feedback as $f)
                            <tr>
                                <td><b>{{ $f->position }}</b> ({{ $f->service_level_text }})</td>
                                <td>{{ $f->feedback_name }}</td>

                                <td>{{$f->pilot_name}} ({{$f->pilot_cid}}), {{$f->pilot_email}}</td>

                                <td data-bs-toggle="tooltip" title="{{ $f->comments }}">{{ str_limit($f->comments, 80, '...') }}</td>
                                <td>{{ $f->created_at }}</td>
                                <td>
                                    <span data-bs-toggle="modal" data-bs-target="#saveFeedback{{ $f->id }}">
                                        <button type="button" class="btn btn-success simple-tooltip" data-bs-placement="top" data-bs-toggle="tooltip" title="Save Feedback"><i class="fas fa-check"></i></button>
                                    </span>
                                    <span data-bs-toggle="modal" data-bs-target="#hideFeedback{{ $f->id }}">
                                        <button type="button" class="btn btn-danger simple-tooltip" data-bs-placement="top" data-bs-toggle="tooltip" title="Hide Feedback"><i class="fas fa-times"></i></button>
                                    </span>
                                    @if($f->pilot_email != null)
                                        <span data-bs-toggle="modal" data-bs-target="#emailFeedback{{ $f->id }}">
                                            <button type="button" class="btn btn-warning simple-tooltip" data-bs-placement="top" data-bs-toggle="tooltip" title="Email Pilot"><i class="fas fa-envelope"></i></button>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="saveFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Save Feedback for {{ $f->feedback_name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        {{ html()->form()->route('saveFeedback', [$f->id])->open() }}
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="feedback_id">Controller/Event</label>
                                                    {{ html()->select('feedback_id', $feedbackOptions, $f->feedback_id)->class(['form-select']) }}
                                                    {{ html()->hidden('event_id', $f->feedback_id) }}
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="position">Position</label>
                                                    {{ html()->text('position', $f->position)->class(['form-control']) }}
                                                </div>
                                            </div>
                                            <br>
                                            <label for="pilot_comments">Pilot Comments</label>
                                            {{ html()->textarea('pilot_comments', $f->comments)->class(['form-control']) }}
                                            <br>
                                            <label for="staff_comments">Staff Comments</label>
                                            {{ html()->textarea('staff_comments', $f->staff_comments)->class(['form-control']) }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button action="submit" class="btn btn-success">Save Feedback</button>
                                        </div>
                                        {{ html()->form()->close() }}
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="hideFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Hide Feedback for {{ $f->feedback_name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        {{ html()->form()->route('hideFeedback', [$f->id])->open() }}
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="feedback_id">Controller/Event</label>
                                                    {{ html()->select('feedback_id', $feedbackOptions, $f->feedback_id)->class(['form-select']) }}
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="position">Position</label>
                                                    {{ html()->text('position', $f->position)->class(['form-control']) }}
                                                </div>
                                            </div>
                                            <br>
                                            <label for="pilot_comments">Pilot Comments</label>
                                            {{ html()->textarea('pilot_comments', $f->comments)->class(['form-control']) }}
                                            <br>
                                            <label for="staff_comments">Staff Comments</label>
                                            {{ html()->textarea('staff_comments', $f->staff_comments)->class(['form-control']) }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button action="submit" class="btn btn-success">Hide Feedback</button>
                                        </div>
                                        {{ html()->form()->close() }}
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="emailFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Email Pilot, {{ $f->pilot_name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        {{ html()->form()->route('emailFeedback', [$f->id])->open() }}
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="name">Your Name</label>
                                                    {{ html()->text('name', 'vZTL ARTCC Staff')->class(['form-control']) }}
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="email">Your Email</label>
                                                    {{ html()->email('email', 'feedback@notams.ztlartcc.org')->class(['form-control']) }}
                                                </div>
                                            </div>
                                            <br>
                                            <label for="subject">Subject</label>
                                            {{ html()->text('subject', 'An email regarding your recent feedback')->class(['form-control']) }}
                                            <br>
                                            <label for="body">Message</label>
                                            {{ html()->textarea('body', null)->placeholder('Gander Center, Pass your message...')->class(['form-control']) }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button action="submit" class="btn btn-success">Send Email</button>
                                        </div>
                                        {{ html()->form()->close() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            @else
                @include('inc.empty_state', ['header' => 'No New Feedback', 'body' => 'There is no new feedback to show.', 'icon' => 'fa-solid fa-file'])
            @endif
        </div>
        <div role="tabpanel" class="tab-pane" id="processed">
            @if($feedback_p->count() > 0)
                <table class="table table-outline">
                    <thead>
                        <tr>
                            <th scope="col">Position</th>
                            <th scope="col">Controller/Event</th>
                            <th scope="col">Comments</th>
                            <th scope="col">Staff Comments</th>
                            <th scope="col">Processed</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feedback_p as $f)
                        <tr>
                            <td><b>{{ $f->position }}</b> ({{ $f->service_level_text }})</td>
                            <td>{{ $f->feedback_name }}</td>
                            <td data-bs-toggle="tooltip" title="{{ $f->comments }}">{{ str_limit($f->comments, 40, '...') }}</td>
                            <td data-bs-toggle="tooltip" title="{{ $f->staff_comments }}">{{ str_limit($f->staff_comments, 40, '...') }}</td>
                            <td>{{ $f->created_at }}</td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <span data-bs-toggle="modal" data-bs-target="#updateFeedback{{ $f->id }}">
                                            <button type="button" class="btn btn-success simple-tooltip" data-bs-placement="top" data-bs-toggle="tooltip" title="Update Feedback"><i class="fas fa-pencil-alt"></i></button>
                                        </span>
                                    </div>
                                    <div class="col-sm-2">
                                        @if($f->pilot_email != null)
                                            <span data-bs-toggle="modal" data-bs-target="#emailFeedback{{ $f->id }}">
                                                <button type="button" class="btn btn-warning simple-tooltip" data-bs-placement="top" data-bs-toggle="tooltip" title="Email Pilot"><i class="fas fa-envelope"></i></button>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="updateFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Feedback for {{ $f->feedback_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    {{ html()->form()->route('updateFeedback', [$f->id])->open() }}
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="feedback_id">Controller</label>
                                                {{ html()->select('feedback_id', $feedbackOptions, $f->feedback_id)->class(['form-select']) }}
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="position">Position</label>
                                                {{ html()->text('position', $f->position)->class(['form-control']) }}
                                            </div>
                                        </div>
                                        <br>
                                        <label for="pilot_comments">Pilot Comments</label>
                                        {{ html()->textarea('pilot_comments', $f->comments)->class(['form-control']) }}
                                        <br>
                                        <label for="staff_comments">Staff Comments</label>
                                        {{ html()->textarea('staff_comments', $f->staff_comments)->class(['form-control']) }}
                                    </div>
                                    <div class="modal-footer">
                                        {{ html()->select('status', [
                                            0 => 'N/A',
                                            1 => 'Saved',
                                            2 => 'Hidden'
                                        ], $f->status)->class(['form-select']) }}
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button action="submit" class="btn btn-success">Update Feedback</button>
                                    </div>
                                    {{ html()->form()->close() }}
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="emailFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Email Pilot, {{ $f->pilot_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    {{ html()->form()->route('emailFeedback', [$f->id])->open() }}
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="name">Your Name</label>
                                                {{ html()->text('name', 'vZTL ARTCC Staff')->class(['form-control']) }}
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="email">Your Email</label>
                                                {{ html()->email('email', 'feedback@notams.ztlartcc.org')->class(['form-control']) }}
                                            </div>
                                        </div>
                                        <br>
                                        <label for="subject">Subject</label>
                                        {{ html()->text('subject', 'An email regarding your recent feedback')->class(['form-control']) }}
                                        <br>
                                        <label for="body">Message</label>
                                        {{ html()->textarea('body', null)->placeholder('Gander Center, Pass your message...')->class(['form-control']) }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button action="submit" class="btn btn-success">Send Email</button>
                                    </div>
                                    {{ html()->form()->close() }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            @else
                @include('inc.empty_state', ['header' => 'No Processed Feedback', 'body' => 'There is no processed feedback to show.', 'icon' => 'fa-solid fa-file'])
            @endif
            {!! $feedback_p->links() !!}
        </div>
    </div>
</div>
@endsection
