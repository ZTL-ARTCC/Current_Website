@extends('layouts.master')

@section('title')
ZTL Pilot Passport Challenge
@endsection

@push('custom_header')
@endpush

@section('content')
@if(auth()->guard('realops')->guest())
@include('inc.header', ['title' => 'ZTL Pilot Passport Challenge', 'type' => 'external', 'content' => '<a href="/pilot_passport/login" class="btn btn-primary float-end" dusk="login">Login as Pilot</a>'])
@else
@include('inc.header', ['title' => 'ZTL Pilot Passport Challenge', 'type' => 'external', 'content' => '<button disabled class="btn btn-primary float-end">Welcome, ' . auth()->guard('realops')->user()->full_name . '</button>'])
@endif

<div class="container">
    <ul class="nav nav-tabs nav-justified" role="tablist" id="tabMenu">
        <li class="nav-item">
            @php $active = ($tab == 'information') ? ' active' : ''; @endphp
            <a class="nav-link tab-link{{ $active }}" href="#information" role="tab" bs-toggle="tab">Program Information</a>
        </li>
        @if(!auth()->guard('realops')->guest())
        <li class="nav-item">
            @php $active = ($tab == 'enrollments') ? ' active' : ''; @endphp
            <a class="nav-link tab-link{{ $active }}" href="#enrollments" role="tab" data-bs-toggle="tab">Enrollments</a>
        </li>
        <li class="nav-item">
            @php $active = ($tab == 'passport_book') ? ' active' : ''; @endphp
            <a class="nav-link tab-link{{ $active }}" href="#passport_book" role="tab" data-bs-toggle="tab">Passport Book</a>
        </li>
        <li class="nav-item">
            @php $active = ($tab == 'achievements') ? ' active' : ''; @endphp
            <a class="nav-link tab-link{{ $active }}" href="#achievements" role="tab" data-bs-toggle="tab">Achievements</a>
        </li>
        <li class="nav-item">
            @php $active = ($tab == 'settings') ? ' active' : ''; @endphp
            <a class="nav-link tab-link{{ $active }}" href="#settings" role="tab" data-bs-toggle="tab">Settings</a>
        </li>
        @endif
    </ul>
    <div class="tab-content" style="min-height:500px">
        @php $active = ($tab == 'information') ? ' active' : ''; @endphp
        <div role="tabpanel" class="tab-pane p-2 mb-4{{ $active }}" id="information" dusk="info">
            <div class="row">
                <div class="col-sm-8">
                    <h5>What is the ZTL Pilot Passport Challenge?</h5>
                    <p>Are you tired of flying the same routes to the same airports? Do you need a new mission? Are you interested in being more
                        proficient as a pilot? Do you enjoy the service provided by one of the top facilities in VATUSA? Then this challenge is for
                        you! This is a unique quest for VATSIM pilots to visit airfields within the Atlanta Virtual ARTCC's airspace boundary.
                        Challenge yourself to visit airfields served by Atlanta Virtual Air Traffic Controllers and build your skills as a pilot by
                        flying new approaches and taxiing on unfamiliar surfaces. We offer three distinct paths: airline pilot, corporate aviator,
                        and bug smasher The airline path features airports served by part 121 commercial carriers. The business aviator path
                        features airports commonly frequented by part 135 operators flying private jets and turboprops. The bug smasher path
                        features the many smaller airports throughout Georgia, Alabama, South Carolina, North Carolina, Tennessee and Virginia.</p>
                </div>
                <div class="col-sm-4 text-center">
                    <img src="/photos/pilot_passport/pilot_passport_stamp.png" width="200px" alt="stamp">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <h5>What's in it for me?</h5>
                    <p>Pilots who enroll and meet milestones of the challenge will be recognized on the ZTL website. You'll have the opportunity to
                        fill up a virtual passport book with stamps for each airfield you visit. Upon completion of a path, you'll get a badge and
                        a certificate of completion suitable for framing.</p>
                </div>
                <div class="col-sm-6">
                    <h5>How do I get started?</h5>
                    <p>Start at the top of this page and login. Then, enroll in our challenge and pick your path. Each time you visit an airport,
                        you'll get a stamp in your passport. To earn a stamp, you should plan a full-stop landing at a qualifying airport and spend
                        at least 5 minutes on the ground before departing.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h5>Feedback?</h5>
                    <p>Want to tell us what you think about this program? We're always looking for feedback. Send us an email at
                        <a href="mailto:wm@ztlartcc.org" alt="Email_Us">wm@ztlartcc.org</a> or visit our <a href="/feedback/new" alt="Feedback">
                            pilot feedback</a> page.
                    </p>
                </div>
            </div>
        </div>
        @php $active = ($tab == 'enrollments') ? ' active' : ''; @endphp
        <div role="tabpanel" class="tab-pane mb-4{{ $active }}" id="enrollments">
            <div class="row">
                <div class="col-sm-12">
                    <h5>Enrollments</h5>
                    <p>Select a path below and enroll to get started. You may enroll in one or as many challenges as you like!</p>
                    <br>
                </div>
            </div>
            @foreach($challenges as $c)
            <div class="row mb-4">
                <div class="col-sm-3 text-center">
                    <strong>{{ $c->title }}</strong>
                    <br> 
                    @php ($enrolled = false) @endphp
                    @foreach($enrollments as $enrollment)
                        @if($enrollment->challenge_id == $c->id)
                            @php ($enrolled = true) @endphp
                        @endif
                    @endforeach
                    {{ html()->form()->route('pilotPassportEnroll')->open() }}
                    @if(!$enrolled)
                        <button class="btn btn-primary" type="submit" name="challenge_id" value="{{ $c->id }}" dusk="enroll_{{ $c->id }}">Enroll</button>
                    @else
                        <button class="btn btn-secondary" disabled>Enrolled</button>
                    @endif
                    {{ html()->form()->close() }}
                </div>
                <div class="col-sm-9">
                    {{ $c->description }}
                </div>
            </div>
            <hr>
            @endforeach
        </div>
        @php $active = ($tab == 'passport_book') ? ' active' : ''; @endphp
        <div role="tabpanel" class="tab-pane mb-4{{ $active }}" id="passport_book">
            <h5>My Passport Book</h5>
            @if($enrollments->isEmpty())
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                    <h6>No enrollments yet. When you enroll in a challenge, your passport book will show up here.</h6>
                        </div>
                    </div>
            @elseif(count($enrollments) == 1)
                <strong>{{ $enrollment->title }}</strong>
            @else
                <?php
                    $enrolled_challenges = [];
                    foreach ($enrollments as $enrollment) {
                        $enrolled_challenges[$enrollment->challenge_id] = $enrollment->title;
                    }
                ?>
                {{ html()->form()->route('pilotPassportIndex')->open() }}
                {{ html()->hidden('tab', 'passport_book') }}
                {{ html()->select('pg', $enrolled_challenges, $view_challenge)->class(['form-control'])->attributes(['onchange' => 'submit()']) }}
                {{ html()->form()->close() }}
            @endif
            @foreach($enrollments as $enrollment)
                @if(is_null($view_challenge))
                    @php $view_challenge = $enrollment->challenge_id; @endphp
                @endif
                @if($enrollment->challenge_id != $view_challenge)
                    @php continue; @endphp
                @endif
                @php ($closed = false) @endphp
                @foreach($enrollment->airfields as $a)
                    @if($loop->iteration % 3 == 1)
                        <div class="row">
                        @php ($closed = false) @endphp
                    @endif
                    <div class="col-sm-4 text-center">
                        <b>{{ $a->airfield_id }}</b>
                        <br>
                        @if($a->visited)
                            <img src="/pilot_passport/stamp/{{ $a->airfield_id }}" width="150px" alt="stamp">
                            <br>{{ $a->visited->callsign }} 
                            @if($a->visited->aircraft_type != '') 
                                ({{ $a->visited->aircraft_type }})
                            @endif
                            <br>Visited on: {{ $a->visited->visited_on }}
                        @else
                            <img src="/photos/pilot_passport/pilot_passport_stamp_gray.png" width="150px" alt="stamp">
                            <br>No visit logged
                        @endif
                    </div>
                    @if($loop->iteration % 3 == 0)
                        </div>
                        <hr>
                        @php ($closed = true) @endphp
                    @endif
                @endforeach
                @if(!$closed)
                    </div>
                @endif
            @endforeach
        </div>
        @php $active = ($tab == 'achievements') ? ' active' : ''; @endphp
        <div role="tabpanel" class="tab-pane mb-4{{ $active }}" id="achievements">
            <div class="row">
                <div class="col-sm-12">
                    <h5>My Achievements</h5>
                    @foreach($achievements as $achievement)
                    <div class="row">
                        <div class="col-sm-4">
                            <img src="/pilot_passport/medal/{{ $achievement->id }}" width="200px" alt="medal">
                        </div>
                        <div class="col-sm-8">
                            <h3>{{ $achievement->challenge_title }}</h3>
                            <h5>Completed on: {{ $achievement->awarded_on_date }}</h5>
                            <br>
                            <a href="/pilot_passport/certificate/{{ $achievement->id }}" alt="Download_Certificate">Download Certificate</a>
                        </div>
                    </div>
                    @endforeach
                    @if($achievements->isEmpty())
                        <div class="row mb-4">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                            <h6>No program achievements yet. When you complete a challenge, your achievements and certifiate will show up here.</h6>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @php $active = ($tab == 'settings') ? ' active' : ''; @endphp
        <div role="tabpanel" class="tab-pane mb-4{{ $active }}" id="settings">
            <div class="row">
                <div class="col-sm-12">
                    <h5>Privacy Settings</h5>
                    <p>ZTL understands that some pilots may want to participate in this program but wish to remain anonymous or otherwise protect
                        their privacy. No problem! You may adjust your privacy settings below and we'll respect your wishes.
                    </p>
                </div>
            </div>
            {{ html()->form()->route('pilotPassportSettings')->open() }}
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-check form-check-inline">
                        {{ html()->radio('privacy')->value(0)->class(['form-check-input'])->checked(old('privacy', $privacy == 0)) }}
                        <label class="form-check-label">Default</label>
                    </div>
                </div>
                <div class="col-sm-10">
                    <p>When you complete an achievement in the program, your First Name and Last Initial (formatted similar to "John D.") will be
                        shown on ZTL's public-facing website.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-check form-check-inline">
                        {{ html()->radio('privacy')->value(1)->class(['form-check-input'])->checked(old('privacy', $privacy == 1)) }}
                        <label class="form-check-label">First name only</label>
                    </div>
                </div>
                <div class="col-sm-10">
                    <p>When you complete an achievement in the program, your First Name (formatted similar to "Jane") will be
                        shown on ZTL's public-facing website.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-check form-check-inline">
                        {{ html()->radio('privacy')->value(2)->class(['form-check-input'])->checked(old('privacy', $privacy == 2))->attributes(['dusk' => 'privacy']) }}
                        <label class="form-check-label">CID only</label>
                    </div>
                </div>
                <div class="col-sm-10">
                    <p>When you complete an achievement in the program, your VATSIM CID (formatted similar to "1234567") will be
                        shown on ZTL's public-facing website.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-check form-check-inline">
                        {{ html()->radio('privacy')->value(3)->class(['form-check-input'])->checked(old('privacy', $privacy == 3)) }}
                        <label class="form-check-label">No public recognition</label>
                    </div>
                </div>
                <div class="col-sm-10">
                    <p>When you complete an achievement in the program, you will not be identified on ZTL's public-facing website. Your achievement
                        will still be visible to you protected by your login on this site.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <button class="btn btn-success" type="submit">Save Settings</button>
                </div>
            </div>
            {{ html()->form()->close() }}
            <hr class="mb-4">
            <div class="row">
                <div class="col-sm-2">
                    <span data-bs-toggle="modal" data-bs-target="#purge">
                        <button type="button" class="btn btn-danger me-2" data-bs-toggle="tooltip" dusk="purge_data">Disenroll /<br>Purge Data</button>
                    </span>
                </div>
                <div class="col-sm-10">
                    <p>If you need to leave this program and remove all of your personal data from our system (in accordance with protections
                        provided to you by various privacy laws), you may do so by clicking this button. Be advised that this action is
                        irreversible - we have no way of recovering your progress or achievements. If you are currently registered for a
                        ZTL Realops Event, purging your data here will also remove you from our Realops system. This action has no effect on
                        your VATSIM account. For VATSIM privacy and data protection options, please visit
                        <a href="https://vatsim.net/docs/policy/data-protection-and-handling-policy" target="_blank" alt="VATSIM_DPHP">VATSIM DPHP</a>.
                    </p>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="purge" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Disenroll/Purge Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ html()->form()->route('pilotPassportPurgeData')->open() }}
            @csrf
            <div class="modal-body">
                <p>Danger! This will permanently delete your pilot record with us to include your progress in the Pilot Passport program and Realops bids. Type <b>confirm - purge all</b> to proceed.</p>
                {{ html()->text('confirm_text', null)->class(['form-control'])->placeholder('confirm - purge all')->attributes(['dusk' => 'confirm']) }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button action="submit" class="btn btn-danger">Continue</button>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
</div>
@endsection
