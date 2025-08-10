<div class="container">
    {{ html()->form()->route('saveNewTrainerFeedback')->open() }}
        @php ($redirect_to = (isset($redirect)) ? $redirect : 'external')
        {{ html()->text('redirect_to', $redirect_to)->attribute('hidden') }}
        <div class="row mb-3">
            <div class="col-sm-4 form-group">
                <label for="feedback_id" class="control-label">Training Team Member: (required)</label>
                {{ html()->select('feedback_id', $feedbackOptions)->placeholder('Select Team Member')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="feedback_date" class="control-label">Date of Session/Event: (required)</label>
                    <div class="input-group date dt_picker_date" id="datetimepicker1" data-td-target-input="nearest" data-td-target-toggle="nearest">
                        {{ html()->text('feedback_date', null)->placeholder('MM/DD/YYYY')->class(['form-control','datetimepicker-input'])->attributes(['data-td-target' => '#datetimepicker1']) }}
                        <span class="input-group-text" data-td-target="#datetimepicker1" data-td-toggle="datetimepicker">
                            <i class="fas fa-calendar"></i>
                        </span>
                    </div>
            </div>
            <div class="col-sm-4 form-group">
                <label for="service_level" class="control-label">Service Level: (required)</label>
                <div id="stars" class="star-input input-group">
                        <span data-rating="1">&star;</span>
                        <span data-rating="2">&star;</span>
                        <span data-rating="3">&star;</span>
                        <span data-rating="4">&star;</span>
                        <span data-rating="5">&star;</span>
                        {{ html()->text('service_level', null)->attribute('hidden') }}
                    </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4 form-group">
                <label for="position_trained" class="control-label">Position Trained/Training Session ID: (required)</label>
                {{ html()->text('position_trained', null)->placeholder('Position Staffed')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="booking_method" class="control-label">Booking Method: (required)</label>
                {{ html()->select('booking_method', [0=>'Scheddy', 1=>'Ad-Hoc'])->placeholder('Pick One')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="training_method" class="control-label">Training Method: (required)</label>
                {{ html()->select('training_method', [0=>'Theory', 1=>'Sweatbox', 2=>'Live Network'])->placeholder('Pick One')->class(['form-control']) }}
            </div>
        </div>
        <div class="row form-group mb-3">
            <div class="col-sm-12 form-group">
                <label for="comments" class="control-label">Comments: (required)</label>
                {{ html()->textarea('comments', null)->placeholder('Type your comments here. This will be reviewed by the facility TA and ATA.')->class(['form-control']) }}
            </div>
        </div>
        <div class="row border border-danger rounded p-2">
            <div class="col-sm-4 form-group">
                <label for="student_name" class="control-label">Your Name: (optional)</label>
                {{ html()->text('student_name', null)->placeholder('Your Name')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="student_email" class="control-label">Your Email: (optional)</label>
                {{ html()->email('student_email', null)->placeholder('Your Email')->class(['form-control']) }}
            </div>
            <div class="col-sm-4 form-group">
                <label for="student_cid" class="control-label">Your VATSIM CID: (optional)</label>
                {{ html()->text('student_cid', null)->placeholder('Your VATSIM CID')->class(['form-control']) }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <p class="strong text-danger"><i class="fa-solid fa-shield-halved"></i>&nbsp;&nbsp;This feedback is anonymous if you do not fill out your personal information. You will not be 
                    identified and we will be unable to follow-up with you regarding your comments. For additional privacy, you may
                    access this form while logged-out at <a href="{{ URL::to('/trainer_feedback/new') }}" alt="external link" target="_blank">{{ URL::to('/trainer_feedback/new') }}</a></p>
            </div>
        </div>
        <div class="g-recaptcha" data-sitekey="{{ config('google.site_key') }}"></div>
        <br>
        <button class="btn btn-success mb-2" type="submit">Send Feedback</button>
    {{ html()->form()->close() }}
</div>
<script src="{{mix('js/trainerfeedback.js')}}"></script>
