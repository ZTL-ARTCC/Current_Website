<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
        @if(Carbon\Carbon::now()->month == 12)
            <a class="navbar-brand" href="/dashboard"><img width="100" src="/photos/xmas_logo.png"></a>
        @else
            <a class="navbar-brand" href="/dashboard"><img width="100" src="/photos/logo.png"></a>
        @endif
            <ul class="navbar-nav">
                {!! Form::open(['action' => 'ControllerDash@searchAirport']) !!}
                    <div class="form-inline">
                        {!! Form::text('apt', null, ['placeholder' => 'Search Airport ICAO', 'class' => 'form-control']) !!}
                        &nbsp;
                        <button class="btn btn-success" type="submit">Search</button>
                    </div>
                {!! Form::close() !!}
            </ul>
            &nbsp;&nbsp;&nbsp;
            <ul>
                <br />
                Receive Broadcast Emails?
                &nbsp;
                @if(Auth::user()->opt == 1)
                    <span data-toggle="modal" data-target="#unOpt">
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                    </span>
                @else
                    <span data-toggle="modal" data-target="#Opt">
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </span>
                @endif
            </ul>
            <ul class="navbar-nav ml-auto">
			<a class="nav-link {{ Nav::isRoute('controller_dash_home') }}" href="/dashboard">Dashboard Home</a>
                <li class="nav-item dropdown">
                    <a class="nav-link" style="pointer-events:none">{{ Auth::user()->full_name }} - {{ Auth::user()->rating_short }}</a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<div class="modal fade" id="unOpt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Opt Out of Broadcast Emails?</h5>
            </div>
            <br>
            <div class="container">
                <p>Please note that opting out of broadcast emails will only prevent you from receiving broadcast emails issued from staff. Personalized emails (both automated and issued by staff) will not be affected. If you have any questions, please contact the ATM at <a href="mailto:atm@ztlartcc.org">atm@ztlartcc.org</a>.</p>
            </div>
            <div class="modal-footer">
                <a href="{{ url()->current() }}" class="btn btn-secondary">Close</a>
                <a href="/dashboard/opt/out" class="btn btn-success">Confirm Selection</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Opt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Opt Into Broadcast Emails?</h5>
            </div>
            <br>
            <div class="container">
                <p>Opting into emails will only affect the recieving of mass emails. If you elect to opt into emails, you agree to recieve mass emails sent to groups of members of the vZTL ARTCC. This selection will not affect the reception of personalized emails (both automated and issued by staff) for example, training ticket emails. If you have any questions, please contact the ATM at <a href="mailto:atm@ztlartcc.org">atm@ztlartcc.org</a>.</p>
                <p>You may opt out at any time by using the slider shown at the top of the controller dashboard at all times.</p>
                <br>
                <i>Please check the following check boxes if you would like to continue.</i>
                <hr>
                {!! Form::open(['action' => 'ControllerDash@optIn']) !!}
                    <div class="form-group">
                        {!! Form::checkbox('opt', '1', false) !!}
                        {!! Form::label('opt', 'I agree to recieve mass emails from the vZTL ARTCC.', ['class' => 'form-label']) !!}
                        <br>
                        {!! Form::checkbox('privacy', '1', false) !!}
                        {!! Form::label('privacy', 'I have read and agree to the vZTL ARTCC Privacy Policy.', ['class' => 'form-label']) !!}
                    </div>
            </div>
            <div class="modal-footer">
                <a href="{{ url()->current() }}" class="btn btn-secondary">Close</a>
                <button type="submit" class="btn btn-success">Confirm Selection</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 45px;
  height: 26px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: gray;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: lightgreen;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(20px);
  -ms-transform: translateX(20px);
  transform: translateX(20px);
}


/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
