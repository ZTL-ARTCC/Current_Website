<div class="card bg-light card-body" id="pill-sidebar">
    <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical" style="margin-left:20px;">
        <div class="dropdown-divider"></div>
        <p class="collapsible" name="controllers" style="margin-left:-20px; cursor:pointer">
            ZTL CONTROLLERS
            <b class="caret float-right fas fa-caret-left"></b>
        </p>
        <div class="content">
            @if(Auth::user()->rating_id == 1)
                <a class="nav-link {{ Nav::urlDoesContain('controllers/teamspeak') }}" href="/controllers/teamspeak">Teamspeak Information</a>
            @endif
            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/roster') }} {{ Nav::urlDoesContain('/dashboard/admin/roster') }}" href="/dashboard/controllers/roster">Roster</a>
            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/events') }} {{ Nav::urlDoesContain('dashboard/admin/events') }}" href="/dashboard/controllers/events">Events</a>
            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/bookings') }}" href="/dashboard/controllers/bookings">ATC Bookings</a>
            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/files') }} {{ Nav::urlDoesContain('dashboard/admin/files') }}" href="/dashboard/controllers/files">Files</a>
            <a class="nav-link" href="https://ids.ztlartcc.org">vIDS</a>
            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/scenery') }}" href="/dashboard/controllers/scenery">Scenery</a>
            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/stats') }}" href="/dashboard/controllers/stats">Statistics</a>
            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/incident/report') }}" href="/dashboard/controllers/incident/report">Incident Report</a>
            <div class="dropdown-divider"></div>
            <a class="nav-link {{ Nav::urlDoesContain('/dashboard/controllers/profile') }} {{ Nav::urlDoesContain('/dashboard/controllers/ticket') }}" href="/dashboard/controllers/profile"><i class="fas fa-user"></i> My Profile</a>
            <a class="nav-link" href="/"><i class="fas fa-arrow-circle-left"></i> Return to Main Website</a>
            <a class="nav-link" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        @if(Auth::user()->canTrain == 1 || Auth::user()->isAbleTo('train'))
            <div class="dropdown-divider"></div>
            <p class="collapsible" name="training" style="margin-left:-20px; cursor:pointer">
                TRAINING
                <b class="caret float-right fas fa-caret-left"></b>
            </p>
            <div class="content">
                <a class="nav-link" href="https://scheduling.ztlartcc.org?first_name={{ Auth::user()->fname }}&last_name={{ Auth::user()->lname }}&email={{ Auth::user()->email }}&cid={{ Auth::id() }}" target="_blank">Schedule a Training Session</a>
                <a class="nav-link {{ Nav::urlDoesContain('dashboard/training/info') }}" href="/dashboard/training/info">Training Information</a>
                <a class="nav-link {{ Nav::urlDoesContain('/dashboard/training/atcast') }}" href="/dashboard/training/atcast">ATCast Videos</a>
                @if(Auth::user()->isAbleTo('train'))
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/training/tickets') }}" href="/dashboard/training/tickets">Training Tickets</a>
                    <a class="nav-link" href="https://scheduling.ztlartcc.org/index.php/user/login" target="_blank">Schedule Management</a>
                    @if(Auth::user()->hasRole('ins') || Auth::user()->isAbleTo('snrStaff'))
                        <a class="nav-link {{ Nav::urlDoesContain('dashboard/training/ots-center') }}" href="/dashboard/training/ots-center">OTS Center</a>
                    @endif
                    @if(Auth::user()->isAbleTo('snrStaff'))
                        <a class="nav-link {{ Nav::urlDoesContain('dashboard/training/statistics') }}" href="/dashboard/training/statistics">TA Dashboard</a>
                    @endif
                @endif
            </div>
        @endif
        @if(Auth::user()->isAbleTo('staff') || Auth::user()->isAbleTo('email') || Auth::user()->isAbleTo('scenery') || Auth::user()->isAbleTo('files'))
            <div class="dropdown-divider"></div>
            <p class="collapsible" name="administration" style="margin-left:-20px; cursor:pointer">
                ADMINISTRATION
                <b class="caret float-right fas fa-caret-left"></b>
            </p>
            <div class="content">
                @if(Auth::user()->isAbleTo('staff'))
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/calendar') }}" href="/dashboard/admin/calendar">Calendar/News</a>
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/airports') }}" href="/dashboard/admin/airports">Airport Management</a>
                @endif
                @if(Auth::user()->isAbleTo('scenery'))
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/scenery') }}" href="/dashboard/admin/scenery">Scenery Management</a>
                @endif
                @if(Auth::user()->isAbleTo('snrStaff'))
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/feedback') }}" href="/dashboard/admin/feedback">Feedback Management</a>
                @endif
                @if(Auth::user()->isAbleTo('email'))
                    <a class="nav-link" href="https://accounts.zoho.in/" target="_blank">Staff Webmail</a>
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/email/send') }}" href="/dashboard/admin/email/send">Send New Email</a>
                @endif
                @if(Auth::user()->isAbleTo('staff'))
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/announcement') }}" href="/dashboard/admin/announcement">Announcement</a>
                @endif
                @if(Auth::user()->isAbleTo('snrStaff'))
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/incident') }}" href="/dashboard/admin/incident">Incident Report Management</a>
                @endif
                @if(Auth::user()->isAbleTo('roster'))
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/bronze-mic') }}" href="/dashboard/admin/bronze-mic">Award Management</a>
                @endif
                @if(Auth::user()->isAbleTo('staff') || Auth::user()->hasRole('events-team'))
                    @toggle('realops')
                        <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/realops') }}" href="/dashboard/admin/realops">Realops</a>
                    @endtoggle
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/toggles') }}" href="/dashboard/admin/toggles">Feature Toggles</a>
                @endif
                @if(Auth::user()->isAbleTo('snrStaff'))
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/audits') }}" href="/dashboard/admin/audits">Website Activity</a>
                @endif
                @if(Auth::user()->isAbleTo('staff'))
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/monitor') }}" href="/dashboard/admin/monitor">Background Task Monitor</a>
                @endif
                @if(Auth::user()->hasRole('wm') || Auth::user()->hasRole('awm'))
                    <a class="nav-link {{ Nav::urlDoesContain('laratrust') }}" href="/laratrust">Laratrust Panel</a>
                @endif
                @if(Auth::user()->isAbleTo('events'))
                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/events/denylist') }}" href="/dashboard/admin/events/denylist">Event Denylist</a>
                @endif

            </div>
        @endif
        <div class="dropdown-divider"></div>
    </div>
</div>
<br>

@if(isset($home) && isset($controllers))
<div class="card">
	<div class="card-body p-2">
		<h5 class="card-title">{{ Carbon\Carbon::now()->translatedFormat('F') }} Leaders&nbsp;<i class="fas fa-medal"></i></h5>
		<table class="table table-sm table-borderless table-striped pb-0 mb-0">		
		@if(count($home) > 0)
            @foreach($home as $h)
				<tr class="p-3 m-0"><td class="py-0 pl-1 pr-2 m-0"><strong>{{ $h->full_name }}</strong></td><td class="py-0 px-2 m-0">&nbsp;{{ $stats[$h->id]->bronze_hrs }}</td></tr>
            @endforeach
        @else
            <tr class="p-3 m-0"><td class="py-0 pl-1 pr-2 m-0"><i>So empty...</i></td></tr>
        @endif
		</table>
	</div>
</div>
<br/>
<div class="card">
	<div class="card-body p-2">
		<h5 class="card-title">Online Now&nbsp;<i class="fas fa-broadcast-tower"></i></h5>
		<table class="table table-sm table-borderless table-striped pb-0 mb-0">
        @if($controllers->count() > 0)
            @foreach($controllers as $c)
				<tr class="p-3 m-0"><td class="py-0 pl-1 pr-2 m-0"><strong>{{ $c->name }}</strong></td><td class="py-0 px-2 m-0">&nbsp;{{ $c->time_online }}</td></tr>
            @endforeach
        @else
            <tr class="p-3 m-0"><td class="py-0 pl-1 pr-2 m-0"><i>So empty...</i></td></tr>
        @endif
		</table>
	</div>
</div>

@endif
<script src="{{asset('js/sidebar.js')}}"></script>
