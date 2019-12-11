@if(

    Auth::user()->hasRole('ins') && App\Ots::where('status', 0)->get()->count() > 0 || Auth::user()->hasRole('atm') && App\Ots::where('status', 0)->get()->count() > 0 ||

    App\Ots::where('status', 1)->where('ins_id', Auth::id())->get()->count() > 0 ||

    App\TrainingTicket::where('created_at', '>=', Carbon\Carbon::now()->subHours(24))->where('controller_id', Auth::id())->first() != null ||

    count(App\Incident::where('status', 0)->get()) > 0 && Auth::user()->can('snrStaff') ||

    count(App\Feedback::where('status', 0)->get()) > 0 && Auth::user()->can('snrStaff') ||

    count(App\Visitor::where('status', 0)->get()) > 0 && Auth::user()->can('snrStaff')

    )

    <hr>

    <center><h4><i>Notifications</i></h4></center>

    @if(Auth::user()->hasRole('ins') && App\Ots::where('status', 0)->get()->count() > 0 || Auth::user()->hasRole('atm') && App\Ots::where('status', 0)->get()->count() > 0)

        <br>

        <div class="alert alert-success">

            There is a <b>new OTS recommendation</b> that is waiting to be accepted. View the <a href="/dashboard/training/ots-center">OTS Center</a> to view more information.

        </div>

    @endif



    @if(App\Ots::where('status', 1)->where('ins_id', Auth::id())->get()->count() > 0)

        <br>

        <div class="alert alert-success">

            You have either been assigned to an OTS or you have accepted an OTS that is waiting to take place. View the <a href="/dashboard/training/ots-center">OTS Center</a> to view more information.

        </div>

    @endif



    @if(App\TrainingTicket::where('created_at', '>=', Carbon\Carbon::now()->subHours(24))->where('controller_id', Auth::id())->first() != null)

        <br>

        <div class="alert alert-success">

            You have a <b>new training ticket</b>. Visit <a href="/dashboard/controllers/profile">your profile</a> to view more information.

        </div>

    @endif



    @if(count(App\Incident::where('status', 0)->get()) > 0 && Auth::user()->can('snrStaff'))

        <br>

        <div class="alert alert-success">

            There is a <b>new incident report</b>. Visit <a href="/dashboard/admin/incident">incident reports</a> to view more information.

        </div>

    @endif

    @if(count(App\Feedback::where('status', 0)->get()) > 0 && Auth::user()->can('snrStaff'))

        <br>

        <div class="alert alert-success">

            There is <b>new feedback awaiting review</b>. Visit <a href="/dashboard/admin/feedback">feedback</a> to view more information.

        </div>

    @endif

    @if(count(App\Visitor::where('status', 0)->get()) > 0 && Auth::user()->can('snrStaff'))

        <br>

        <div class="alert alert-success">

            There is a <b>new visitor application</b>. Visit <a href="/dashboard/admin/roster/visit/requests">visitor requests</a> to view more information.

        </div>

    @endif

@endif
