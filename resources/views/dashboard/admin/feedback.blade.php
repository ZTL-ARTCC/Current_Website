@extends('layouts.dashboard')

@section('title')
Feedback Management
@endsection

@section('content')
<div class="container-fluid" style="background-color:#F0F0F0;">
    &nbsp;
    <h2>Feedback Management</h2>
    &nbsp;
</div>
<br>
<div class="container">
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#new" role="tab" data-toggle="tab" style="color:black">New Feedback</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#processed" role="tab" data-toggle="tab" style="color:black">Processed Feedback</a>
        </li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="new">
            @if($feedback->count() > 0)
                <table class="table table-outline">
                    <thead>
                        <tr>
                            <th scope="col">Position</th>
                            <th scope="col">Controller</th>
                            <th scope="col">Comments</th>
                            <th scope="col">Submitted</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feedback as $f)
                            <tr>
                                <td><b>{{ $f->position }}</b> ({{ $f->service_level_text }})</td>
                                <td>{{ $f->controller_name }}</td>
                                <td data-toggle="tooltip" title="{{ $f->comments }}">{{ str_limit($f->comments, 80, '...') }}</td>
                                <td>{{ $f->created_at }}</td>
                                <td>
                                    <span data-toggle="modal" data-target="#saveFeedback{{ $f->id }}">
                                        <button type="button" class="btn btn-success simple-tooltip" data-placement="top" data-toggle="tooltip" title="Save Feedback"><i class="fas fa-check"></i></button>
                                    </span>
                                    <span data-toggle="modal" data-target="#hideFeedback{{ $f->id }}">
                                        <button type="button" class="btn btn-danger simple-tooltip" data-placement="top" data-toggle="tooltip" title="Hide Feedback"><i class="fas fa-times"></i></button>
                                    </span>
                                    @if($f->pilot_email != null)
                                        <span data-toggle="modal" data-target="#emailFeedback{{ $f->id }}">
                                            <button type="button" class="btn btn-warning simple-tooltip" data-placement="top" data-toggle="tooltip" title="Email Pilot"><i class="fas fa-envelope"></i></button>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="saveFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Save Feedback for {{ $f->controller_name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        {!! Form::open(['action' => ['AdminDash@saveFeedback', $f->id]]) !!}
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    {!! Form::label('controller_id', 'Controller') !!}
                                                    {!! Form::select('controller_id', $controllers, $f->controller_id, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-sm-6">
                                                    {!! Form::label('position', 'Position') !!}
                                                    {!! Form::text('position', $f->position, ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <br>
                                            {!! Form::label('pilot_comments', 'Pilot Comments') !!}
                                            {!! Form::textArea('pilot_comments', $f->comments, ['class' => 'form-control']) !!}
                                            <br>
                                            {!! Form::label('staff_comments', 'Staff Comments') !!}
                                            {!! Form::textArea('staff_comments', $f->staff_comments, ['class' => 'form-control']) !!}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button action="submit" class="btn btn-success">Save Feedback</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="hideFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Hide Feedback for {{ $f->controller_name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        {!! Form::open(['action' => ['AdminDash@hideFeedback', $f->id]]) !!}
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    {!! Form::label('controller_id', 'Controller') !!}
                                                    {!! Form::select('controller_id', $controllers, $f->controller_id, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-sm-6">
                                                    {!! Form::label('position', 'Position') !!}
                                                    {!! Form::text('position', $f->position, ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <br>
                                            {!! Form::label('pilot_comments', 'Pilot Comments') !!}
                                            {!! Form::textArea('pilot_comments', $f->comments, ['class' => 'form-control']) !!}
                                            <br>
                                            {!! Form::label('staff_comments', 'Staff Comments') !!}
                                            {!! Form::textArea('staff_comments', $f->staff_comments, ['class' => 'form-control']) !!}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button action="submit" class="btn btn-success">Hide Feedback</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="emailFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Email Pilot, {{ $f->pilot_name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        {!! Form::open(['action' => ['AdminDash@emailFeedback', $f->id]]) !!}
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    {!! Form::label('name', 'Your Name') !!}
                                                    {!! Form::text('name', 'vZTL ARTCC Staff', ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-sm-6">
                                                    {!! Form::label('email', 'Your Email') !!}
                                                    {!! Form::email('email', 'feedback@notams.ztlartcc.org', ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <br>
                                            {!! Form::label('subject', 'Subject') !!}
                                            {!! Form::text('subject', 'An email regarding your recent feedback', ['class' => 'form-control']) !!}
                                            <br>
                                            {!! Form::label('body', 'Message') !!}
                                            {!! Form::textArea('body', null, ['placeholder' => 'Gander Center, Pass your message...', 'class' => 'form-control']) !!}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button action="submit" class="btn btn-success">Send Email</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No new feedback.</p>
            @endif
        </div>
        <div role="tabpanel" class="tab-pane" id="processed">
            @if($feedback_p->count() > 0)
                <table class="table table-outline">
                    <thead>
                        <tr>
                            <th scope="col">Position</th>
                            <th scope="col">Controller</th>
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
                            <td>{{ $f->controller_name }}</td>
                            <td data-toggle="tooltip" title="{{ $f->comments }}">{{ str_limit($f->comments, 40, '...') }}</td>
                            <td data-toggle="tooltip" title="{{ $f->staff_comments }}">{{ str_limit($f->staff_comments, 40, '...') }}</td>
                            <td>{{ $f->created_at }}</td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <span data-toggle="modal" data-target="#updateFeedback{{ $f->id }}">
                                            <button type="button" class="btn btn-success simple-tooltip" data-placement="top" data-toggle="tooltip" title="Update Feedback"><i class="fas fa-pencil-alt"></i></button>
                                        </span>
                                    </div>
                                    <div class="col-sm-2">
                                        @if($f->pilot_email != null)
                                            <span data-toggle="modal" data-target="#emailFeedback{{ $f->id }}">
                                                <button type="button" class="btn btn-warning simple-tooltip" data-placement="top" data-toggle="tooltip" title="Email Pilot"><i class="fas fa-envelope"></i></button>
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
                                        <h5 class="modal-title">Update Feedback for {{ $f->controller_name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    {!! Form::open(['action' => ['AdminDash@updateFeedback', $f->id]]) !!}
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                {!! Form::label('controller_id', 'Controller') !!}
                                                {!! Form::select('controller_id', $controllers, $f->controller_id, ['class' => 'form-control']) !!}
                                            </div>
                                            <div class="col-sm-6">
                                                {!! Form::label('position', 'Position') !!}
                                                {!! Form::text('position', $f->position, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <br>
                                        {!! Form::label('pilot_comments', 'Pilot Comments') !!}
                                        {!! Form::textArea('pilot_comments', $f->comments, ['class' => 'form-control']) !!}
                                        <br>
                                        {!! Form::label('staff_comments', 'Staff Comments') !!}
                                        {!! Form::textArea('staff_comments', $f->staff_comments, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="modal-footer">
                                        {!! Form::select('status', [
                                            0 => 'N/A',
                                            1 => 'Saved',
                                            2 => 'Hidden'
                                        ], $f->status, ['class' => 'form-control']) !!}
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button action="submit" class="btn btn-success">Update Feedback</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="emailFeedback{{ $f->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Email Pilot, {{ $f->pilot_name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    {!! Form::open(['action' => ['AdminDash@emailFeedback', $f->id]]) !!}
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                {!! Form::label('name', 'Your Name') !!}
                                                {!! Form::text('name', 'vZTL ARTCC Staff', ['class' => 'form-control']) !!}
                                            </div>
                                            <div class="col-sm-6">
                                                {!! Form::label('email', 'Your Email') !!}
                                                {!! Form::email('email', 'feedback@notams.ztlartcc.org', ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <br>
                                        {!! Form::label('subject', 'Subject') !!}
                                        {!! Form::text('subject', 'An email regarding your recent feedback', ['class' => 'form-control']) !!}
                                        <br>
                                        {!! Form::label('body', 'Message') !!}
                                        {!! Form::textArea('body', null, ['placeholder' => 'Gander Center, Pass your message...', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button action="submit" class="btn btn-success">Send Email</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No processed feedback.</p>
            @endif
            {!! $feedback_p->links() !!}
        </div>
    </div>
</div>
@endsection
