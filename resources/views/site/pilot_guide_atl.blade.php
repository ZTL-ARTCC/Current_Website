@extends('layouts.master')

@section('title')
ATL Pilot Guide
@endsection

@push('custom_header')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<link rel="stylesheet" href="{{ asset('css/pilots_guide.css') }}" />
@endpush

@section('content')
@php
function fetchBadge($pos) {
$badge = array('position','frequency');
if($pos) {
$badge['position'] = "<span class=\"badge badge-primary\">$pos->position</span>";
$badge['frequency'] = "<span class=\"badge badge-primary\">$pos->freq</span>";
}
else {
$badge['position'] = "<span class=\"badge badge-secondary\">OFFLINE</span>";
$badge['frequency'] = "<span class=\"badge badge-secondary\">OFFLINE</span>";
}
return $badge;
}
@endphp
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Atlanta Pilot Guide</h2>
        &nbsp;
    </div>
</span>
<br>

<div class="container">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab0">Welcome</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab1" onclick="resizeMap();">Connecting</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab2">Communications</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab3">Flight Plans</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab4">Clearances</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab5">Push/Start</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab6">Taxi</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab7">Takeoff & Climb</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab8">Arrival</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab0" class="tab-pane active">
            <img class="float-right m-1" src="/photos/pg_atl_tab0.png">
            <h3>Welcome</h3>
            <p>We are glad that you have decided to fly out of Atlanta today. The Atlanta Hartsfield-Jackson International Airport is the world’s
                busiest airport by passenger volume. At ZTL, we do our very best to simulate real-world operations and air traffic control. Thanks
                for your interest and help in creating a great simulation environment!</p>
        </div>
        <div id="tab1" class="tab-pane fade">
            <h3>Connecting to the network</h3>
            <p> ATL is one of the busiest airports in VATSIM, consistently ranking in the top 10 by both traffic volume and ATC coverage. When
                connecting your simulator at Atlanta, you should first determine where to spawn your aircraft. Take a look at the ramp status map
                below so you don't connect on top of another aircraft!</p>
            <h4>Live ATL gate status</h4>
            <div style="width:100%; height:300px">
                <div id="map" style="position:relative; height:100%; max-width:100%; max-height:100%; cursor:crosshair"></div>
                <div id="legend">
                    <div class="legenditem"><span class="taxiarr"></span> Arrival</div>
                    <div class="legenditem"><span class="taxidep"></span> Departure</div>
                    <div class="legenditem"><span class="nofp"></span> Other</div>
                </div>
            </div>
            <br /><br />
            <div class="container">
                <div class="row">
                    <div class="col">Here are some general guidelines regarding gate use at ATL:</div>
                </div>
                <div class="row">
                    <div class="col">
                        <ul>
                            <li>Concourse T
                                <ul>
                                    <li>Gates T1-T8: Delta</li>
                                    <li>Gates T10-T12: American</li>
                                    <li>Gates T13-17: United</li>
                                </ul>
                            </li>
                            <li>Concourse A: Delta domestic</li>
                            <li>Concourse B: Delta domestic</li>
                            <li>Concourse C
                                <ul>
                                    <li>Gates C1-C22: Southwest</li>
                                    <li>Gates C30-C55: Delta</li>
                                </ul>
                            </li>
                            <li>Concourse D
                                <ul>
                                    <li>Gates D1/D1A/D3: Frontier</li>
                                    <li>Gates D2/D4: Spirit</li>
                                    <li>Gates D21/D23/D24/D25: American</li>
                                    <li>All other gates: Delta connection</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="col">
                        <ul>
                            <li>Concourse E
                                <ul>
                                    <li>Gates E1/E3: Spirit</li>
                                    <li>Gate E2: Frontier</li>
                                    <li>Gate E32: JetBlue</li>
                                    <li>Gate E34: United</li>
                                    <li>All other gates: Delta domestic/international</li>
                                </ul>
                            </li>
                            <li>Concourse F
                                <ul>
                                    <li>Gate F1: British Airways & Korean Air</li>
                                    <li>Gate F2: Virgin Atlantic & Delta</li>
                                    <li>Gate F3: Lufthansa, Turkish & Delta</li>
                                    <li>Gates F4/F6/F8/F9/F10/F12: Delta</li>
                                    <li>Gate F5: Qatar</li>
                                    <li>Gate F7: KLM & Air France</li>
                                    <li>Gate F14: Air Canada & Delta</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab2" class="tab-pane fade">
            <h3>Who should I talk to?</h3>
            <p> Controllers come and go on VATSIM and unlike the real-world, staffing in control positions is not consistent throughout the day.
                It can be complicated to figure out who you should talk to! Similar to real-world facilities, we use the “top-down” control method,
                where higher facilities controller lower ones when the lower facility is not staffed. Here are a couple of examples:</p>
            <p>You are departing from ATL. Atlanta Clearance Delivery is not online, but Atlanta Approach is online. Approach is performing the
                duties of clearance delivery, ground, tower, and approach at ATL.</p>
            <p>You are departing from ATL. Atlanta Tower and Atlanta Center are the only controllers logged in. You will talk to Atlanta Tower for
                clearance delivery, ground, and tower. You will talk to Atlanta Center for approach and enroute services.</p>
            <h4>Current ATC staffing at Atlanta:</h4>
            <div class="table">
                <table class="table table-bordered table-sm">
                    <thead>
                        <th scope="col">
                            <center>Position</center>
                        </th>
                        <th scope="col">
                            <center>Frequency</center>
                        </th>
                        <th scope="col">
                            <center>Controller</center>
                        </th>
                        <th scope="col">
                            <center>Rating</center>
                        </th>
                    </thead>
                    <tbody>
                        @php
                        $del = $gnd = $twr = $dep = $ctr = FALSE;
                        @endphp
                        @if(count($controllers) > 0)
                        @foreach($controllers as $c)
                        @php
                        if(is_numeric(strpos($c->position,'CTR'))&&!$ctr)
                        $ctr = $c;
                        elseif(is_numeric(strpos($c->position,'DEP'))&&!$dep)
                        $dep = $c;
                        elseif(is_numeric(strpos($c->position,'APP'))&&!$dep)
                        $dep = $c;
                        elseif(is_numeric(strpos($c->position,'TWR'))&&!$twr)
                        $twr = $c;
                        elseif(is_numeric(strpos($c->position,'GND'))&&!$gnd)
                        $gnd = $c;
                        elseif(is_numeric(strpos($c->position,'DEL'))&&!$del)
                        $del = $c;
                        @endphp
                        <tr>
                            <td>
                                <center>{{ $c->position }}</center>
                            </td>
                            <td>
                                <center>{{ $c->freq }}</center>
                            </td>
                            <td>
                                <center>{{ $c->name }}</center>
                            </td>
                            @if(App\User::find($c->cid) != null)
                            <td>
                                <center>{{ App\User::find($c->cid)->rating_long }}</center>
                            </td>
                            @else
                            <td>
                                <center><i>Rating Not Available</i></center>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        @php
                        if(!$ctr)
                        $ctr = FALSE;
                        if(!$dep)
                        $dep = $ctr;
                        if(!$twr)
                        $twr = $dep;
                        if(!$gnd)
                        $gnd = $twr;
                        if(!$del)
                        $del = $gnd;
                        @endphp
                        @else
                        <tr>
                            <td colspan="6">
                                <center><i>No Controllers Online</i></center>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="6">
                                <div align="right"><i class="fas fa-sync-alt fa-spin"></i> Last Updated {{ $controllers_update }}Z</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p>If more than one ground controller is online at ATL, you should call GC-N/121.9 for taxi to runway 8R/26L or GC-C/121.75 for taxi
                to runway 9L/27R.</p>
            <p>If more than 1 tower controller is online at ATL, you should call the controller on the frequency provided by ground.</p>
        </div>
        <div id="tab3" class="tab-pane fade">
            <img class="float-right m-1" src="/photos/pg_atl_tab3.png">
            <h3>Filing a flight plan</h3>
            <p> All IFR operations at ATL require a flight plan on-file. We highly recommend that all VFR operators file flight plans. If you
                cannot file a flight plan, a controller will assist you (workload depending).</p>
            <p>IFR flight plans should file an appropriate RNAV standard departure procedure. For aircraft that are not RNAV-capable, file the
                ATL departure.</p>
            <p>One common area of confusion for Atlanta involves filed altitudes for departures to destinations in Florida. Because of Florida's
                geographic north-south orientation, certain areas of airspace do not operate on traditional north-odd/south-even (NEODD/SWEVEN)
                rules. You may expect a change in altitude while en route flying along the eastern part of Florida. Additionally, because of ATL’s
                proximity to north Florida, there are some restrictions regarding final altitudes. Here are some common routes and acceptable
                altitudes.</p>
        </div>
        <div id="tab4" class="tab-pane fade">
            <div id="diagram1Carousel" class="carousel slide w-25 float-right">
                <br />
                <a href="https://vats.im/ATLDPAAUP" target="_blank"><img src="/photos/atl_aaup.png" class="d-block w-100" alt="AAUP"></a>
            </div>
            <h3>Receiving a clearance</h3>
            <p> After filing a flight plan, you should receive your clearance from ATC. If you have not received a clearance after a reasonable
                about of time, you should call {!! fetchBadge($del)['position'] !!} on {!! fetchBadge($del)['frequency'] !!} and request your
                clearance.</p>
            <p class="font-italic">Example: Atlanta Clearance, Delta 1234 clearance to Charlotte ready to copy.</p>
            <p>Aircraft with CPDLC capabilities should expect to receive a computerized pre-departure clearance from ATC. Because ATC sends this
                type of clearance directly to your aircraft, a read back is not necessary.</p>
            <p>Aircraft without CPDLC should expect an abbreviated or full-route clearance from ATC. When calling for your clearance, be prepared
                to write it down. Some pilots write the letters C-R-A-F-T vertically on a sheet of paper to assist in copying a clearance as read
                by ATC. After issuing your clearance, ATC expects you to reply with at least your callsign and squawk code to confirm that you
                copied the information correctly. At your discretion you may readback your entire clearance to verify information was copied
                correctly. If you have questions about your route of flight, you should ask clearance delivery. Voice clearances may not include an
                altitude or departure frequency. In this case, use the information published on the appropriate departure procedure chart.</p>
            <p>You should listen to ATIS at this time. The ATIS provides important information regarding weather and NOTAMs at the airfield. ATL
                publishes both an arrival ATIS and a departure ATIS – you should listen to the one applicable to your operation.</p>
            <p>Take the time now to ensure that your FMS or GPS flight plan matches the clearance that ATC issued to you. Your departure runway
                is determined by the standard instrument departure (SID) that you were issued, your requirements for takeoff, and your parking
                location on the airfield. Here is a guide to determine your departure runway:</p>
            <ul>
                <li>ATL departs runways 8R/26L and 9L/27R. Do not expect other runways for departure.</li>
                <li>Runway 9L/27R is the longest runway at ATL at 12,390 feet. If your aircraft’s takeoff data indicates that you need this runway
                    for departure, you should inform the ground controller on first contact.</li>
                <li>If parked at concourse T, A, B, C, D, E, F, your departure runway is dependent on your filed SID. See chart on the right side
                    of this page.</li>
                <li>If you are departing from the GA ramp/Signature or the north cargo ramp, you should expect runway 8R/26L for departure.</li>
                <li>If you are departing from the South Cargo ramp, you should expect runway 9L/27R for departure.</li>
            </ul>
        </div>
        <div id="tab5" class="tab-pane fade">
            <img class="float-right m-1" src="/photos/pg_atl_tab5.png">
            <h3>Push & Start</h3>
            <p> ATL does not simulate ramp control. During non-event periods, push and start clearances are at your discretion and do not require
                ATC clearance. For major events (example: cross the pond) where you have been issued an estimated departure clearance time (EDCT),
                you may be required to call ground prior to push. Do not call ground for push/start unless the ATIS or your PDC advises you to do so.</p>
            <p>Exception: If you must push onto an active taxiway, you are required to call {!! fetchBadge($gnd)['position'] !!} on
                {!! fetchBadge($gnd)['frequency'] !!} and request clearance to do
                so. Gate E36 is the only gate at ATL that may require push back onto an active taxiway for Boeing 757 and larger aircraft.</p>
            <p>Make sure that you push the correct direction! If you are departing on runway 8R/26L, you want to face north. If you are departing
                on runway 9L/27R you should be facing south.</p>
            <p>Refer to the <a href="https://vats.im/ATLDPAAUP" alt="AAUP">Attention All Users Page (AAUP)</a> for your expected runway based on your
                SID and push at the appropriate north or south end of the ramp. This resource is available on the 'taxi' tab of this guide. If your
                PDC tells you to call at the north or south end specifically, disregard the AAUP and expect the runway corresponding to that
                direction.</p>
        </div>
        <div id="tab6" class="tab-pane fade">
            <img class="float-right m-1" src="/photos/pg_atl_tab6.png">
            <h3>Taxi</h3>
            <p> Check ATIS one more time prior to calling for taxi to ensure that nothing has changed. Ensure that your transponder is on with
                the correct squawk code entered and in altitude mode. Taxi to the north or south hold point on the ramp and call
                {!! fetchBadge($gnd)['position'] !!} on {!! fetchBadge($gnd)['frequency'] !!}. Inform the controller of your ramp location
                (see airfield diagram) and provide them with the ATIS code.</p>
            <p>*Note: If more than one ground controller is online at ATL, you should call GC-N/121.9 for taxi to runway 8R/26L or GC-C/121.75 for taxi
                to runway 9L/27R.</p>
            <p class="font-italic">Example: Atlanta Ground, Delta 1234 ramp 1 south taxi with A.</p>
            <p>The controller will respond with taxi instructions to a runway. You should have an airfield diagram available so you can follow
                the precise route given to the runway. If you are not assigned a runway intersection, you should taxi to the hold point at the
                end of the runway. If you are assigned an intersection, make sure that you hold at the intersection. Note: intersections that are
                within 500 feet of the end of the runway are considered full-length departures. Hold points are commonly used on runway 9L (M2)
                and 27R (LB, LC).</p>
            <div id="diagram2Carousel" class="carousel slide w-25 float-right" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <a href="{{ $diag }}" target="_blank"><img src="/photos/atl_diag.png" class="d-block w-100" alt="Diagram"></a>
                    </div>
                    <div class="carousel-item">
                        <a href="https://vats.im/ATLDPAAUP" target="_blank"><img src="/photos/atl_aaup.png" class="d-block w-100" alt="AAUP"></a>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#diagram2Carousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#diagram2Carousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <p>Taxiway D at Atlanta between G and L is a non-movement area and is not controlled by ATC. If you are taxiing to a runway and intend
                to use D, you should still call ATC prior to proceeding on any taxiway.</p>
            <p>When taxiing to runway 26L, expect a clearance via F. Pilots should continue taxiing on E to the runway unless given specific
                instructions to hold short.</p>
            <p>Aircraft may be instructed to hold midfield to reduce congestion at the end of the runway. Follow all hold short instructions</p>
            <p>If you are instructed to contact the next controller, you should call them with your location. If you are instructed to monitor the
                next controller, tune your radio to the proper frequency and wait for ATC to call you. ATL has airport surface detection equipment
                (ASDE), so controllers can monitor the precise location of all aircraft on the ground at all times.</p>
        </div>
        <div id="tab7" class="tab-pane fade">
            <img class="float-right m-1" src="/photos/pg_atl_tab7.png">
            <h3>Takeoff & Climbout</h3>
            <p>As part of your takeoff clearance, the controller will state your initial RNAV waypoint or your departure heading. These items must
                be read back to the controller issuing your takeoff clearance. This step ensures that you will remain clear of traffic departing
                on parallel runways between takeoff and your initial contact with radar control. When you read back your first RNAV waypoint,
                verify that the first point in your FMS LEGS page matches the point provided by the controller. If the point does not match, tell
                the controller "UNABLE" and discuss the discrepancy with them. It may be necessary for a controller to verify a pilot's departure
                name, number and first waypoint.</p>
            <p class="font-italic">Example: Delta 1234, Atlanta Tower RNAV to SLAWW runway 27R clear for takeoff.</p>
            <p>Precisely follow the instructions given by tower to ensure that you remain clear of traffic departing on parallel runways. If you do
                not understand the instruction, just ask! Wait to switch to departure frequency until instructed to do so.</p>
            <p>Turbojets should accelerate to 250 knots without delay.</p>
            <p>Ensure that you do not exceed the initial altitude given in your clearance (10,000 feet). Contact departure control on the published
                frequency when instructed to "contact departure" unless provided another frequency. Atlanta Departure (a.k.a. Approach) will monitor
                your climb-out and provide you with a hand-off to Atlanta Center.</p>
        </div>
        <div id="tab8" class="tab-pane fade">
            <img class="float-right m-1" src="/photos/pg_atl_tab8.png">
            <h3>Arrival</h3>
            <p>Welcome to Atlanta – we’re glad that you decided to make us your destination today. Ensure that you monitor the arrival ATIS when
                you are inbound (no later than 60 miles from the airport). Atlanta Approach is responsible for assigning your arrival runway –
                Center will not be able to do this unless Center is also controlling the Approach position.</p>
            <p>To check-in with Atlanta Approach, provide your callsign, current altitude and the altitude that you are descending to, your location
                on the arrival, your current indicated airspeed, and the current arrival ATIS code. For example: <span class="font-italic">Atlanta Approach DAL69 FL180
                    descending 13,000 at CHPPR speed 250 with Yankee</span></p>
            <p>On initial contact with Atlanta Approach, you will be assigned an arrival runway and an approach type. If you have a special request
                for a different runway or approach, you should state that request at this time. Aircraft parking at the GA ramp (Signature) and
                North Cargo should expect runway 8L/26R. Aircraft parking at the South Cargo ramp should expect runway 9R/27L. All other aircraft
                will be assigned runways based on traffic flow.</p>
            <p>After you are established on an arrival and approach has issued a landing runway, check your FMS to ensure that you have the entire
                arrival and approach programmed correctly. RNAV arrivals and approaches should be continuous with no discontinuity.</p>
            <p>At peak traffic periods, six or more controllers may be working positions in the Atlanta large terminal radar approach control
                facility (TRACON). It is important that you listen to the controller’s instructions during a hand-off to ensure that you contact the
                proper controller. If you switch to the wrong frequency, go back to your previous frequency and ask for clarification.</p>
            <p>For planning purposes and to manage traffic flow, you should expect to fly 210 knots on downwind and once cleared for the approach no
                less than 180 knots until the final approach fix. ATC will provide specific instructions if other speeds are necessary.</p>
            <p>On landing roll-out, taxi clear of the runway in the direction of your preferred parking location and hold short of the parallel
                taxiway. Remain on the tower frequency until instructed to monitor or contact ground. If given a crossing instruction, listen to the
                taxi instructions for the other side of the runway you will cross. Parking gate assignment is at the discretion of the pilot – not
                ATC. When given instructions to taxi to parking, you may select any open gate.</p>
            <div class="container">
                <div class="row">
                    <div class="col">Here are some general guidelines regarding gate use at ATL:</div>
                </div>
                <div class="row">
                    <div class="col">
                        <ul>
                            <li>Concourse T
                                <ul>
                                    <li>Gates T1-T8: Delta</li>
                                    <li>Gates T10-T12: American</li>
                                    <li>Gates T13-17: United</li>
                                </ul>
                            </li>
                            <li>Concourse A: Delta domestic</li>
                            <li>Concourse B: Delta domestic</li>
                            <li>Concourse C
                                <ul>
                                    <li>Gates C1-C22: Southwest</li>
                                    <li>Gates C30-C55: Delta</li>
                                </ul>
                            </li>
                            <li>Concourse D
                                <ul>
                                    <li>Gates D1/D1A/D3: Frontier</li>
                                    <li>Gates D2/D4: Spirit</li>
                                    <li>Gates D21/D23/D24/D25: American</li>
                                    <li>All other gates: Delta connection</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="col">
                        <ul>
                            <li>Concourse E
                                <ul>
                                    <li>Gates E1/E3: Spirit</li>
                                    <li>Gate E2: Frontier</li>
                                    <li>Gate E32: JetBlue</li>
                                    <li>Gate E34: United</li>
                                    <li>All other gates: Delta domestic/international</li>
                                </ul>
                            </li>
                            <li>Concourse F
                                <ul>
                                    <li>Gate F1: British Airways & Korean Air</li>
                                    <li>Gate F2: Virgin Atlantic & Delta</li>
                                    <li>Gate F3: Lufthansa, Turkish & Delta</li>
                                    <li>Gates F4/F6/F8/F9/F10/F12: Delta</li>
                                    <li>Gate F5: Qatar</li>
                                    <li>Gate F7: KLM & Air France</li>
                                    <li>Gate F14: Air Canada & Delta</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const centroid = [33.64079, -84.43295];
    const maxLatLon = [33.66, -84.39];
    const minLatLon = [33.61, -84.46];
</script>
{{Html::script(asset('js/pilots_guide.js'))}}
@endsection