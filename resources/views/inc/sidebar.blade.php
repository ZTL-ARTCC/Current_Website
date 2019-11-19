<div class="card bg-light card-body" id="pill-sidebar">

    <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical" style="margin-left:20px;">

        <div class="dropdown-divider"></div>

        <p class="collapsible-controllers" style="margin-left:-20px; cursor:pointer">

            ZTL CONTROLLERS

            <b id="caret-controllers" class="float-right fas fa-caret-left"></b>

        </p>

        <div class="content">

            @if(Auth::user()->rating_id == 1)

                <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/teamspeak') }}" href="/dashboard/controllers/teamspeak">Teamspeak Information</a>

            @endif

            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/roster') }} {{ Nav::urlDoesContain('/dashboard/admin/roster') }}" href="/dashboard/controllers/roster">Roster</a>

            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/events') }} {{ Nav::urlDoesContain('dashboard/admin/events') }}" href="/dashboard/controllers/events">Events</a>

            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/files') }} {{ Nav::urlDoesContain('dashboard/admin/files') }}" href="/dashboard/controllers/files">Files</a>

            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/scenery') }}" href="/dashboard/controllers/scenery">Scenery</a>

            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/stats') }}" href="/dashboard/controllers/stats">Statistics</a>

            <a class="nav-link {{ Nav::urlDoesContain('dashboard/controllers/incident/report') }}" href="/dashboard/controllers/incident/report">Incident Report</a>

            <div class="dropdown-divider"></div>

            <a class="nav-link {{ Nav::urlDoesContain('/dashboard/controllers/profile') }} {{ Nav::urlDoesContain('/dashboard/controllers/ticket') }}" href="/dashboard/controllers/profile"><i class="fas fa-user"></i> My Profile</a>

            <a class="nav-link" href="/"><i class="fas fa-arrow-circle-left"></i> Return to Main Website</a>

            <a class="nav-link" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>

        </div>

        @if(Auth::user()->canTrain == 1 || Auth::user()->can('train'))

            <div class="dropdown-divider"></div>

            <p class="collapsible-train" style="margin-left:-20px; cursor:pointer">

                TRAINING

                <b id="caret-train" class="float-right fas fa-caret-left"></b>

            </p>

            <div class="content">

                <a class="nav-link" href="/dashboard/training/moodle/login" target="_blank">Access Moodle</a>

                <script id="setmore_script" type="text/javascript" src="https://my.setmore.com/webapp/js/src/others/setmore_iframe.js"></script><a id="Setmore_button_iframe"  class="nav-link" href="https://my.setmore.com/bookingpage/3598990c-a847-4107-81eb-de1794648684">Schedule a Training Session</a>

                <a class="nav-link {{ Nav::urlDoesContain('dashboard/training/info') }}" href="/dashboard/training/info">Training Information</a>

                <a class="nav-link {{ Nav::urlDoesContain('/dashboard/training/atcast') }}" href="/dashboard/training/atcast">ATCast Videos</a>

                @if(Auth::user()->can('train'))

                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/training/tickets') }}" href="/dashboard/training/tickets">Training Tickets</a>

                    <a class="nav-link" href="https://my.setmore.com/" target="_blank">Schedule Management</a>

                    @if(Auth::user()->hasRole('ins') || Auth::user()->can('snrStaff'))

                        <a class="nav-link {{ Nav::urlDoesContain('dashboard/training/ots-center') }}" href="/dashboard/training/ots-center">OTS Center</a>

                    @endif

                @endif

            </div>

        @endif

        @if(Auth::user()->can('staff') || Auth::user()->can('email') || Auth::user()->can('scenery') || Auth::user()->can('files') || Auth::user()->can('events'))

            <div class="dropdown-divider"></div>

            <p class="collapsible-admin" style="margin-left:-20px; cursor:pointer">

                ADMINISTRATION

                <b id="caret-admin" class="float-right fas fa-caret-left"></b>

            </p>

            <div class="content">

                @if(Auth::user()->can('staff'))

                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/calendar') }}" href="/dashboard/admin/calendar">Calendar/News</a>

                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/airports') }}" href="/dashboard/admin/airports">Airport Management</a>

                @endif

                @if(Auth::user()->can('scenery'))

                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/scenery') }}" href="/dashboard/admin/scenery">Scenery Management</a>

                @endif

                @if(Auth::user()->can('snrStaff'))

                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/feedback') }}" href="/dashboard/admin/feedback">Feedback Management</a>

                @endif

                @if(Auth::user()->can('email'))

                    <a class="nav-link" href="http://mail.ztlartcc.org" target="_blank">Email</a>

                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/email/send') }}" href="/dashboard/admin/email/send">Send New Email</a>

                @endif

                @if(Auth::user()->can('staff'))

                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/announcement') }}" href="/dashboard/admin/announcement">Announcement</a>

                @endif

                @if(Auth::user()->can('snrStaff'))

                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/incident') }}" href="/dashboard/admin/incident">Incident Report Management</a>

                @endif

                @if(Auth::user()->can('roster'))

                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/bronze-mic') }}" href="/dashboard/admin/bronze-mic">Bronze Mic Management</a>

                @endif

                @if(Auth::user()->can('snrStaff'))

                    <a class="nav-link {{ Nav::urlDoesContain('dashboard/admin/audits') }}" href="/dashboard/admin/audits">Website Activity</a>

                @endif

            </div>

        @endif

        <div class="dropdown-divider"></div>

    </div>

</div>

<br>

@include('inc.chat')



<style>

    .collapsible {

    cursor: pointer;

    }

    .content {

        text-align: left;

        overflow: hidden;

        min-height: 0;

        max-height: 0;

        transition: max-height 0.5s ease-out;

    }

    .open {

        transform: rotate(-90deg);

    }



</style>

<script>

    var coll = document.getElementsByClassName("collapsible-controllers");

    var i;

    var iconc = document.getElementById("caret-controllers");



    for (i = 0; i < coll.length; i++) {

      coll[i].addEventListener("click", function() {

        iconc.classList.toggle('open');

        this.classList.toggle("active");

        var content = this.nextElementSibling;



        if (content.style.maxHeight){

          content.style.maxHeight = null;

        } else {

          content.style.maxHeight = content.scrollHeight + "px";

        }

      });



      for (i = 0; i < coll.length; i++) {

          if (coll[i].nextElementSibling.innerHTML.indexOf("active") !== -1) {

              coll[i].click();

          }

      }

  }



  var coll = document.getElementsByClassName("collapsible-train");

  var i;

  var icont = document.getElementById("caret-train");



  for (i = 0; i < coll.length; i++) {

    coll[i].addEventListener("click", function() {

      icont.classList.toggle('open');

      this.classList.toggle("active");

      var content = this.nextElementSibling;

      if (content.style.maxHeight){

        content.style.maxHeight = null;

      } else {

        content.style.maxHeight = content.scrollHeight + "px";

      }

    });



    for (i = 0; i < coll.length; i++) {

        if (coll[i].nextElementSibling.innerHTML.indexOf("active") !== -1) {

            coll[i].click();

        }

    }

}



var coll = document.getElementsByClassName("collapsible-admin");

var i;

var icona = document.getElementById("caret-admin");



for (i = 0; i < coll.length; i++) {

  coll[i].addEventListener("click", function() {

    icona.classList.toggle('open');

    this.classList.toggle("active");

    var content = this.nextElementSibling;

    if (content.style.maxHeight){

      content.style.maxHeight = null;

    } else {

      content.style.maxHeight = content.scrollHeight + "px";

    }

  });



  for (i = 0; i < coll.length; i++) {

      if (coll[i].nextElementSibling.innerHTML.indexOf("active") !== -1) {

          coll[i].click();

      }

  }

}

</script>
