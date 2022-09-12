@extends('layouts.master')

@section('title')
ATL Pilot Guide
@endsection

@push('custom_header')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <style>
   .rotate-1{transform: rotate(1deg) !important; }.rotate-2{transform: rotate(2deg) !important; }.rotate-3{transform: rotate(3deg) !important; }
   .rotate-4{transform: rotate(4deg) !important; }.rotate-5{transform: rotate(5deg) !important; }.rotate-6{transform: rotate(6deg) !important; }
   .rotate-7{transform: rotate(7deg) !important; }.rotate-8{transform: rotate(8deg) !important; }.rotate-9{transform: rotate(9deg) !important; }
   .rotate-10{transform: rotate(10deg) !important; }.rotate-11{transform: rotate(11deg) !important; }.rotate-12{transform: rotate(12deg) !important; }
   .rotate-13{transform: rotate(13deg) !important; }.rotate-14{transform: rotate(14deg) !important; }.rotate-15{transform: rotate(15deg) !important; }
   .rotate-16{transform: rotate(16deg) !important; }.rotate-17{transform: rotate(17deg) !important; }.rotate-18{transform: rotate(18deg) !important; }
   .rotate-19{transform: rotate(19deg) !important; }.rotate-20{transform: rotate(20deg) !important; }.rotate-21{transform: rotate(21deg) !important; }.rotate-22{transform: rotate(22deg) !important; }.rotate-23{transform: rotate(23deg) !important; }.rotate-24{transform: rotate(24deg) !important; }.rotate-25{transform: rotate(25deg) !important; }.rotate-26{transform: rotate(26deg) !important; }.rotate-27{transform: rotate(27deg) !important; }.rotate-28{transform: rotate(28deg) !important; }.rotate-29{transform: rotate(29deg) !important; }.rotate-30{transform: rotate(30deg) !important; }.rotate-31{transform: rotate(31deg) !important; }.rotate-32{transform: rotate(32deg) !important; }.rotate-33{transform: rotate(33deg) !important; }.rotate-34{transform: rotate(34deg) !important; }.rotate-35{transform: rotate(35deg) !important; }.rotate-36{transform: rotate(36deg) !important; }.rotate-37{transform: rotate(37deg) !important; }.rotate-38{transform: rotate(38deg) !important; }.rotate-39{transform: rotate(39deg) !important; }.rotate-40{transform: rotate(40deg) !important; }.rotate-41{transform: rotate(41deg) !important; }.rotate-42{transform: rotate(42deg) !important; }.rotate-43{transform: rotate(43deg) !important; }.rotate-44{transform: rotate(44deg) !important; }.rotate-45{transform: rotate(45deg) !important; }.rotate-46{transform: rotate(46deg) !important; }.rotate-47{transform: rotate(47deg) !important; }.rotate-48{transform: rotate(48deg) !important; }.rotate-49{transform: rotate(49deg) !important; }.rotate-50{transform: rotate(50deg) !important; }.rotate-51{transform: rotate(51deg) !important; }.rotate-52{transform: rotate(52deg) !important; }.rotate-53{transform: rotate(53deg) !important; }.rotate-54{transform: rotate(54deg) !important; }.rotate-55{transform: rotate(55deg) !important; }.rotate-56{transform: rotate(56deg) !important; }.rotate-57{transform: rotate(57deg) !important; }.rotate-58{transform: rotate(58deg) !important; }.rotate-59{transform: rotate(59deg) !important; }.rotate-60{transform: rotate(60deg) !important; }.rotate-61{transform: rotate(61deg) !important; }.rotate-62{transform: rotate(62deg) !important; }.rotate-63{transform: rotate(63deg) !important; }.rotate-64{transform: rotate(64deg) !important; }.rotate-65{transform: rotate(65deg) !important; }.rotate-66{transform: rotate(66deg) !important; }.rotate-67{transform: rotate(67deg) !important; }.rotate-68{transform: rotate(68deg) !important; }.rotate-69{transform: rotate(69deg) !important; }.rotate-70{transform: rotate(70deg) !important; }.rotate-71{transform: rotate(71deg) !important; }.rotate-72{transform: rotate(72deg) !important; }.rotate-73{transform: rotate(73deg) !important; }.rotate-74{transform: rotate(74deg) !important; }.rotate-75{transform: rotate(75deg) !important; }.rotate-76{transform: rotate(76deg) !important; }.rotate-77{transform: rotate(77deg) !important; }.rotate-78{transform: rotate(78deg) !important; }.rotate-79{transform: rotate(79deg) !important; }.rotate-80{transform: rotate(80deg) !important; }.rotate-81{transform: rotate(81deg) !important; }.rotate-82{transform: rotate(82deg) !important; }.rotate-83{transform: rotate(83deg) !important; }.rotate-84{transform: rotate(84deg) !important; }.rotate-85{transform: rotate(85deg) !important; }.rotate-86{transform: rotate(86deg) !important; }.rotate-87{transform: rotate(87deg) !important; }.rotate-88{transform: rotate(88deg) !important; }.rotate-89{transform: rotate(89deg) !important; }.rotate-90{transform: rotate(90deg) !important; }.rotate-91{transform: rotate(91deg) !important; }.rotate-92{transform: rotate(92deg) !important; }.rotate-93{transform: rotate(93deg) !important; }.rotate-94{transform: rotate(94deg) !important; }.rotate-95{transform: rotate(95deg) !important; }.rotate-96{transform: rotate(96deg) !important; }.rotate-97{transform: rotate(97deg) !important; }.rotate-98{transform: rotate(98deg) !important; }.rotate-99{transform: rotate(99deg) !important; }.rotate-100{transform: rotate(100deg) !important; }.rotate-101{transform: rotate(101deg) !important; }.rotate-102{transform: rotate(102deg) !important; }.rotate-103{transform: rotate(103deg) !important; }.rotate-104{transform: rotate(104deg) !important; }.rotate-105{transform: rotate(105deg) !important; }.rotate-106{transform: rotate(106deg) !important; }.rotate-107{transform: rotate(107deg) !important; }.rotate-108{transform: rotate(108deg) !important; }.rotate-109{transform: rotate(109deg) !important; }.rotate-110{transform: rotate(110deg) !important; }.rotate-111{transform: rotate(111deg) !important; }.rotate-112{transform: rotate(112deg) !important; }.rotate-113{transform: rotate(113deg) !important; }.rotate-114{transform: rotate(114deg) !important; }.rotate-115{transform: rotate(115deg) !important; }.rotate-116{transform: rotate(116deg) !important; }.rotate-117{transform: rotate(117deg) !important; }.rotate-118{transform: rotate(118deg) !important; }.rotate-119{transform: rotate(119deg) !important; }.rotate-120{transform: rotate(120deg) !important; }.rotate-121{transform: rotate(121deg) !important; }.rotate-122{transform: rotate(122deg) !important; }.rotate-123{transform: rotate(123deg) !important; }.rotate-124{transform: rotate(124deg) !important; }.rotate-125{transform: rotate(125deg) !important; }.rotate-126{transform: rotate(126deg) !important; }.rotate-127{transform: rotate(127deg) !important; }.rotate-128{transform: rotate(128deg) !important; }.rotate-129{transform: rotate(129deg) !important; }.rotate-130{transform: rotate(130deg) !important; }.rotate-131{transform: rotate(131deg) !important; }.rotate-132{transform: rotate(132deg) !important; }.rotate-133{transform: rotate(133deg) !important; }.rotate-134{transform: rotate(134deg) !important; }.rotate-135{transform: rotate(135deg) !important; }.rotate-136{transform: rotate(136deg) !important; }.rotate-137{transform: rotate(137deg) !important; }.rotate-138{transform: rotate(138deg) !important; }.rotate-139{transform: rotate(139deg) !important; }.rotate-140{transform: rotate(140deg) !important; }.rotate-141{transform: rotate(141deg) !important; }.rotate-142{transform: rotate(142deg) !important; }.rotate-143{transform: rotate(143deg) !important; }.rotate-144{transform: rotate(144deg) !important; }.rotate-145{transform: rotate(145deg) !important; }.rotate-146{transform: rotate(146deg) !important; }.rotate-147{transform: rotate(147deg) !important; }.rotate-148{transform: rotate(148deg) !important; }.rotate-149{transform: rotate(149deg) !important; }.rotate-150{transform: rotate(150deg) !important; }.rotate-151{transform: rotate(151deg) !important; }.rotate-152{transform: rotate(152deg) !important; }.rotate-153{transform: rotate(153deg) !important; }.rotate-154{transform: rotate(154deg) !important; }.rotate-155{transform: rotate(155deg) !important; }.rotate-156{transform: rotate(156deg) !important; }.rotate-157{transform: rotate(157deg) !important; }.rotate-158{transform: rotate(158deg) !important; }.rotate-159{transform: rotate(159deg) !important; }.rotate-160{transform: rotate(160deg) !important; }.rotate-161{transform: rotate(161deg) !important; }.rotate-162{transform: rotate(162deg) !important; }.rotate-163{transform: rotate(163deg) !important; }.rotate-164{transform: rotate(164deg) !important; }.rotate-165{transform: rotate(165deg) !important; }.rotate-166{transform: rotate(166deg) !important; }.rotate-167{transform: rotate(167deg) !important; }.rotate-168{transform: rotate(168deg) !important; }.rotate-169{transform: rotate(169deg) !important; }.rotate-170{transform: rotate(170deg) !important; }.rotate-171{transform: rotate(171deg) !important; }.rotate-172{transform: rotate(172deg) !important; }.rotate-173{transform: rotate(173deg) !important; }.rotate-174{transform: rotate(174deg) !important; }.rotate-175{transform: rotate(175deg) !important; }.rotate-176{transform: rotate(176deg) !important; }.rotate-177{transform: rotate(177deg) !important; }.rotate-178{transform: rotate(178deg) !important; }.rotate-179{transform: rotate(179deg) !important; }.rotate-180{transform: rotate(180deg) !important; }.rotate-181{transform: rotate(181deg) !important; }.rotate-182{transform: rotate(182deg) !important; }.rotate-183{transform: rotate(183deg) !important; }.rotate-184{transform: rotate(184deg) !important; }.rotate-185{transform: rotate(185deg) !important; }.rotate-186{transform: rotate(186deg) !important; }.rotate-187{transform: rotate(187deg) !important; }.rotate-188{transform: rotate(188deg) !important; }.rotate-189{transform: rotate(189deg) !important; }.rotate-190{transform: rotate(190deg) !important; }.rotate-191{transform: rotate(191deg) !important; }.rotate-192{transform: rotate(192deg) !important; }.rotate-193{transform: rotate(193deg) !important; }.rotate-194{transform: rotate(194deg) !important; }.rotate-195{transform: rotate(195deg) !important; }.rotate-196{transform: rotate(196deg) !important; }.rotate-197{transform: rotate(197deg) !important; }.rotate-198{transform: rotate(198deg) !important; }.rotate-199{transform: rotate(199deg) !important; }.rotate-200{transform: rotate(200deg) !important; }.rotate-201{transform: rotate(201deg) !important; }.rotate-202{transform: rotate(202deg) !important; }.rotate-203{transform: rotate(203deg) !important; }.rotate-204{transform: rotate(204deg) !important; }.rotate-205{transform: rotate(205deg) !important; }.rotate-206{transform: rotate(206deg) !important; }.rotate-207{transform: rotate(207deg) !important; }.rotate-208{transform: rotate(208deg) !important; }.rotate-209{transform: rotate(209deg) !important; }.rotate-210{transform: rotate(210deg) !important; }.rotate-211{transform: rotate(211deg) !important; }.rotate-212{transform: rotate(212deg) !important; }.rotate-213{transform: rotate(213deg) !important; }.rotate-214{transform: rotate(214deg) !important; }
   .rotate-215{transform: rotate(215deg) !important; }.rotate-216{transform: rotate(216deg) !important; }.rotate-217{transform: rotate(217deg) !important; }.rotate-218{transform: rotate(218deg) !important; }.rotate-219{transform: rotate(219deg) !important; }.rotate-220{transform: rotate(220deg) !important; }.rotate-221{transform: rotate(221deg) !important; }.rotate-222{transform: rotate(222deg) !important; }.rotate-223{transform: rotate(223deg) !important; }.rotate-224{transform: rotate(224deg) !important; }.rotate-225{transform: rotate(225deg) !important; }.rotate-226{transform: rotate(226deg) !important; }.rotate-227{transform: rotate(227deg) !important; }.rotate-228{transform: rotate(228deg) !important; }.rotate-229{transform: rotate(229deg) !important; }.rotate-230{transform: rotate(230deg) !important; }.rotate-231{transform: rotate(231deg) !important; }.rotate-232{transform: rotate(232deg) !important; }.rotate-233{transform: rotate(233deg) !important; }.rotate-234{transform: rotate(234deg) !important; }.rotate-235{transform: rotate(235deg) !important; }.rotate-236{transform: rotate(236deg) !important; }.rotate-237{transform: rotate(237deg) !important; }.rotate-238{transform: rotate(238deg) !important; }.rotate-239{transform: rotate(239deg) !important; }.rotate-240{transform: rotate(240deg) !important; }.rotate-241{transform: rotate(241deg) !important; }.rotate-242{transform: rotate(242deg) !important; }.rotate-243{transform: rotate(243deg) !important; }.rotate-244{transform: rotate(244deg) !important; }.rotate-245{transform: rotate(245deg) !important; }.rotate-246{transform: rotate(246deg) !important; }.rotate-247{transform: rotate(247deg) !important; }.rotate-248{transform: rotate(248deg) !important; }.rotate-249{transform: rotate(249deg) !important; }.rotate-250{transform: rotate(250deg) !important; }.rotate-251{transform: rotate(251deg) !important; }.rotate-252{transform: rotate(252deg) !important; }.rotate-253{transform: rotate(253deg) !important; }.rotate-254{transform: rotate(254deg) !important; }.rotate-255{transform: rotate(255deg) !important; }.rotate-256{transform: rotate(256deg) !important; }.rotate-257{transform: rotate(257deg) !important; }.rotate-258{transform: rotate(258deg) !important; }.rotate-259{transform: rotate(259deg) !important; }.rotate-260{transform: rotate(260deg) !important; }.rotate-261{transform: rotate(261deg) !important; }.rotate-262{transform: rotate(262deg) !important; }.rotate-263{transform: rotate(263deg) !important; }.rotate-264{transform: rotate(264deg) !important; }.rotate-265{transform: rotate(265deg) !important; }.rotate-266{transform: rotate(266deg) !important; }.rotate-267{transform: rotate(267deg) !important; }.rotate-268{transform: rotate(268deg) !important; }.rotate-269{transform: rotate(269deg) !important; }.rotate-270{transform: rotate(270deg) !important; }.rotate-271{transform: rotate(271deg) !important; }.rotate-272{transform: rotate(272deg) !important; }.rotate-273{transform: rotate(273deg) !important; }.rotate-274{transform: rotate(274deg) !important; }.rotate-275{transform: rotate(275deg) !important; }.rotate-276{transform: rotate(276deg) !important; }.rotate-277{transform: rotate(277deg) !important; }.rotate-278{transform: rotate(278deg) !important; }.rotate-279{transform: rotate(279deg) !important; }.rotate-280{transform: rotate(280deg) !important; }.rotate-281{transform: rotate(281deg) !important; }.rotate-282{transform: rotate(282deg) !important; }.rotate-283{transform: rotate(283deg) !important; }.rotate-284{transform: rotate(284deg) !important; }.rotate-285{transform: rotate(285deg) !important; }.rotate-286{transform: rotate(286deg) !important; }.rotate-287{transform: rotate(287deg) !important; }.rotate-288{transform: rotate(288deg) !important; }.rotate-289{transform: rotate(289deg) !important; }.rotate-290{transform: rotate(290deg) !important; }.rotate-291{transform: rotate(291deg) !important; }.rotate-292{transform: rotate(292deg) !important; }.rotate-293{transform: rotate(293deg) !important; }.rotate-294{transform: rotate(294deg) !important; }.rotate-295{transform: rotate(295deg) !important; }.rotate-296{transform: rotate(296deg) !important; }.rotate-297{transform: rotate(297deg) !important; }.rotate-298{transform: rotate(298deg) !important; }.rotate-299{transform: rotate(299deg) !important; }.rotate-300{transform: rotate(300deg) !important; }.rotate-301{transform: rotate(301deg) !important; }.rotate-302{transform: rotate(302deg) !important; }.rotate-303{transform: rotate(303deg) !important; }.rotate-304{transform: rotate(304deg) !important; }.rotate-305{transform: rotate(305deg) !important; }.rotate-306{transform: rotate(306deg) !important; }.rotate-307{transform: rotate(307deg) !important; }.rotate-308{transform: rotate(308deg) !important; }.rotate-309{transform: rotate(309deg) !important; }.rotate-310{transform: rotate(310deg) !important; }.rotate-311{transform: rotate(311deg) !important; }.rotate-312{transform: rotate(312deg) !important; }.rotate-313{transform: rotate(313deg) !important; }.rotate-314{transform: rotate(314deg) !important; }.rotate-315{transform: rotate(315deg) !important; }.rotate-316{transform: rotate(316deg) !important; }.rotate-317{transform: rotate(317deg) !important; }.rotate-318{transform: rotate(318deg) !important; }.rotate-319{transform: rotate(319deg) !important; }.rotate-320{transform: rotate(320deg) !important; }.rotate-321{transform: rotate(321deg) !important; }.rotate-322{transform: rotate(322deg) !important; }.rotate-323{transform: rotate(323deg) !important; }.rotate-324{transform: rotate(324deg) !important; }.rotate-325{transform: rotate(325deg) !important; }.rotate-326{transform: rotate(326deg) !important; }.rotate-327{transform: rotate(327deg) !important; }.rotate-328{transform: rotate(328deg) !important; }.rotate-329{transform: rotate(329deg) !important; }.rotate-330{transform: rotate(330deg) !important; }.rotate-331{transform: rotate(331deg) !important; }.rotate-332{transform: rotate(332deg) !important; }.rotate-333{transform: rotate(333deg) !important; }.rotate-334{transform: rotate(334deg) !important; }.rotate-335{transform: rotate(335deg) !important; }.rotate-336{transform: rotate(336deg) !important; }.rotate-337{transform: rotate(337deg) !important; }.rotate-338{transform: rotate(338deg) !important; }.rotate-339{transform: rotate(339deg) !important; }.rotate-340{transform: rotate(340deg) !important; }.rotate-341{transform: rotate(341deg) !important; }.rotate-342{transform: rotate(342deg) !important; }.rotate-343{transform: rotate(343deg) !important; }.rotate-344{transform: rotate(344deg) !important; }.rotate-345{transform: rotate(345deg) !important; }.rotate-346{transform: rotate(346deg) !important; }.rotate-347{transform: rotate(347deg) !important; }.rotate-348{transform: rotate(348deg) !important; }.rotate-349{transform: rotate(349deg) !important; }.rotate-350{transform: rotate(350deg) !important; }.rotate-351{transform: rotate(351deg) !important; }.rotate-352{transform: rotate(352deg) !important; }.rotate-353{transform: rotate(353deg) !important; }.rotate-354{transform: rotate(354deg) !important; }.rotate-355{transform: rotate(355deg) !important; }.rotate-356{transform: rotate(356deg) !important; }.rotate-357{transform: rotate(357deg) !important; }.rotate-358{transform: rotate(358deg) !important; }.rotate-359{transform: rotate(359deg) !important; }.rotate-360{transform: rotate(360deg) !important; } 

   #legend {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}
.legenditem {
  margin: 10px;
}
.legenditem span {
  border: 1px solid #ccc; float: left; width: 12px; height: 12px; margin: 2px;
}
.legenditem .nofp {
  background-color: purple;
}
.legenditem .taxiarr {
  background-color: red;
}
.legenditem .taxidep {
  background-color: green;
}
.carousel-control-next,
.carousel-control-prev {
    filter: invert(100%);
}
</style>
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
    <p>	ATL is one of the busiest airports in VATSIM, consistently ranking in the top 10 by both traffic volume and ATC coverage. When 
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
  <br/><br/>
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
                <li>Gates D23-D25: American</li>
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
    <p>	Controllers come and go on VATSIM and unlike the real-world, staffing in control positions is not consistent throughout the day. 
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
                <th scope="col"><center>Position</center></th>
                <th scope="col"><center>Frequency</center></th>
                <th scope="col"><center>Controller</center></th>
                <th scope="col"><center>Rating</center></th>
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
                            <td><center>{{ $c->position }}</center></td>
                            <td><center>{{ $c->freq }}</center></td>
                            <td><center>{{ $c->name }}</center></td>
                            @if(App\User::find($c->cid) != null)
                                <td><center>{{ App\User::find($c->cid)->rating_long }}</center></td>
                            @else
                                <td><center><i>Rating Not Available</i></center></td>
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
                        <td colspan="6"><center><i>No Controllers Online</i></center></td>
                    </tr>
                @endif
                <tr>
                    <td colspan="6"><div align="right"><i class="fas fa-sync-alt fa-spin"></i> Last Updated {{ $controllers_update }}Z</div></td>
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
    <p>	All IFR operations at ATL require a flight plan on-file. We highly recommend that all VFR operators file flight plans. If you 
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
    <br/>
    <a href="https://vats.im/ATLDPAAUP" target="_blank"><img src="/photos/atl_aaup.png" class="d-block w-100" alt="AAUP"></a>
  </div>
<!--
    <div class="float-right m-1 w-25">
        <table class="table table-sm table-striped">
            <thead>
                <tr><th>Departure<br/>Procedure</th><th>Runway</br>Expected</th></tr>
            </thead>
            <tbody>
            <tr><td>ATL</td><td>Ask Ground</td></tr>
            <tr><td>BAANG</td><td>9L/27R</td></tr>
            <tr><td>CUTTN</td><td>8R/26L</td></tr>
            <tr><td>GAIRY</td><td>9L/27R</td></tr>
            <tr><td>HAALO</td><td>9L/27R</td></tr>
            <tr><td>JACCC</td><td>9L/27R</td></tr>
            <tr><td>KAJIN</td><td>8R/26L</td></tr>
            <tr><td>NASSA</td><td>8R/26L</td></tr>
            <tr><td>PADGT</td><td>8R/26L</td></tr>
            <tr><td>PENCL</td><td>8R/26L</td></tr>
            <tr><td>PHIIL</td><td>9L/27R</td></tr>
            <tr><td>PLMMR</td><td>9L/27R</td></tr>
            <tr><td>POUNC</td><td>8R/26L</td></tr>
            <tr><td>SMKEY</td><td>8R/26L</td></tr>
            <tr><td>SMLTZ</td><td>9L/27R</td></tr>
            <tr><td>VARNM</td><td>8R/26L</td></tr>
            <tr><td>VRSTY</td><td>9L/27R</td></tr>
            <tr><td>WIGLE</td><td>9L/27R</td></tr>
            <tr><td>ZELAN</td><td>9L/27R</td></tr>
            </tbody>
        </table>
        <span>*Subject to change based on traffic demand and real-time conditions. Listen to departure ATIS to determine flow direction
            and active runways in-use.</span>
    </div>
-->
    <h3>Receiving a clearance</h3>
    <p>	After filing a flight plan, you should receive your clearance from ATC. If you have not received a clearance after a reasonable 
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
        <li>Runway 9L/29R is the longest runway at ATL at 12,390 feet. If your aircraft’s takeoff data indicates that you need this runway 
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
    <p>	ATL does not simulate ramp control. During non-event periods, push and start clearances are at your discretion and do not require 
        ATC clearance. For major events (example: cross the pond) where you have been issued an estimate departure clearance time (EDCT), 
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
    <p>	Check ATIS one more time prior to calling for taxi to ensure that nothing has changed. Ensure that your transponder is on with 
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
                <li>Gates D23-D25: American</li>
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
	var map = L.map('map').setView([33.64079, -84.43295], 15);
    L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        subdomains: ['a','b','c'],
        maxZoom: 18,
	    minZoom: 15,
    }).addTo( map );

    var planeLayer = new L.LayerGroup();
    planeLayer.addTo(map);

function resizeMap() {
    setTimeout(function() {
        map.invalidateSize();
        updatePlanes();
    }, 500);
}

function updatePlanes() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        resp = JSON.parse(this.responseText);
        planeLayer.clearLayers();
        $.each(resp.data, function(i) {
          if ((resp.data[i].lat<maxLatLon[0])&&(resp.data[i].lon<maxLatLon[1])&&(resp.data[i].lat>minLatLon[0])&&(resp.data[i].lon>minLatLon[1])) { // Enforce bounding box and airborne logic
				createPlane(resp.data[i].lat, resp.data[i].lon, resp.data[i].hdg, resp.data[i].callsign, resp.data[i].type, resp.data[i].dep, resp.data[i].arr);
          }
        });
    }
    };
    xhttp.open("GET", "https://ids.ztlartcc.org/asx/vatusa_api_fetch_aircraft.php", true);
    xhttp.send();
    }

    function createPlane(lat, lon, hdg, cs, actype, dep, arr, sel=false) {
		var color = null;
        if(arr == 'KATL')
            color = 'red'
        else if(dep == 'KATL')
            color = 'green'
        else
            color = 'purple'
      var myIcon = L.divIcon({
        html: '<img src="https://ids.ztlartcc.org/asx/planes/' + color + '.png" class="rotate-' + hdg + '">',
        className: 'trackedAircraft'
      })
      lat = parseFloat(lat)
      lon = parseFloat(lon)
      var marker = L.marker([lat, lon], {icon: myIcon}).bindPopup('<span class="row1">' + cs + "</span><br> \
        <span class=\"row2\">" + dep + " - " + arr +"</span><br> \
        <span class=\"row3\">" + actype + "</span>");
      marker.on('mouseover', function (e) {
        this.openPopup();
      });
      marker.on('mouseout', function (e) {
        this.closePopup();
      });
      this.planeLayer.addLayer(marker);
    }

    setInterval(function() { updatePlanes(); }, 1 * 15 * 1000); // Every 15 seconds

// Set scroll bounding box
const maxLatLon = [33.66,-84.39];
const minLatLon = [33.61,-84.46];

var southWest = L.latLng(minLatLon[0],minLatLon[1]),
northEast = L.latLng(maxLatLon[0],maxLatLon[1]);
var bounds = L.latLngBounds(southWest, northEast);

map.setMaxBounds(bounds);
map.on('drag', function() {
    map.panInsideBounds(bounds, { animate: false });
});

</script>
@endsection
