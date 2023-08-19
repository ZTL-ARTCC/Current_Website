@extends('layouts.master')

@section('title')
Teamspeak
@endsection

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>ZTL ARTCC Teamspeak Information</h2>
        &nbsp;
    </div>
</span>
<br>

<div class="container">
    <center><h1><a href="https://www.teamspeak.com/?tour=yes" target="_blank"><img width="50" src="/photos/ts_stacked.png"></a> vZTL ARTCC Teamspeak Information <a href="https://www.teamspeak.com/?tour=yes" target="_blank"><img width="50" src="/photos/ts_stacked.png"></a></h1></center>
    <center><p><i>Thank you to TeamSpeak for providing us with our TeamSpeak license. Check them out at <a href="https://www.teamspeak.com/?tour=yes" target="_blank">https://www.teamspeak.com</a> for more information!</i></p></center>
    <hr>
    <center><h3>Teamspeak is used for all training and all controller communications. All members are welcome to join us in the teamspeak. You can connect to the teamspeak server without a password, using the server:</h3></center>
    <center><h2><a href="ts3server://ts.ztlartcc.org?port=9987">ts.ztlartcc.org<br>Port: 9987</a></h2></center>
    <hr>
    <center><h2>Teamspeak Rules:</h2></center>
    <div class="row">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-8">
            <ul>
                <li>All users must join the teamspeak using the name associated with their VATSIM account.</li>
                <li>Anonymous users will be kicked with a warning and banned upon reconnecting anonymously.</li>
                <li>Teamspeak permissions are required to move within the teamspeak. Please contact a staff member to receive appropiate permissions. (If you are not a member of the VATUSA region, please provide your CID for rating verification).</li>
                <li>Streaming while controlling is allowed and encouraged although the audio from the teamspeak is not allowed for the privacy of other controllers. Streaming teamspeak audio requires the written permission of the ATM (Contact the ATM at <a href="atm@ztlartcc.org">atm@ztlartcc.org</a> for permission). Streaming audio without his/her permission may result in loss of teamspeak privileges.</li>
                <li>Controlling rooms are limited to controlling only. If a controller asks for you to leave, please do so.</li>
                <li>Use of the teamspeak is a privilage and can be revoked by a staff member at any time and for any reason. To appeal teamspeak bans, please contact the DATM at <a href="datm@ztlartcc.org">datm@ztlartcc.org</a>.</li>
            </ul>
            <p>Please review the <a href="https://www.ztlartcc.org/asset/TeamSpeakDiscordPolicy">TeamSpeak and Discord Policy</a> for the full list of rules prior to joining the TeamSpeak server.</p>
        </div>
        <div class="col-sm-2">
        </div>

            </div> 
<hr>
    <center><h2>Link your TeamSpeak3 PC Client UUID to your ZTL Controller Profile to assign your roles:</h2></center>
    <div class="row">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-8">
            <ul>
                <li>If connected to the ZTL TeamSpeak3 server, disconnect</li>
				<li>In your TeamSpeak 3 menu go to Tools -> Identities (Ctrl-I)</li>
                <li>Highlight & right click to copy your Unique ID to your clipboard</li>
                <li>Proceed to your <a href="https://www.ztlartcc.org/dashboard/controllers/profile">ZTL Controller Dashboard</a> and scroll to the bottom to find your TS3 UID field</li>
                <li>Paste your TeamSpeak3 UUID into the text box and click the Save button</li>
                <li>Log into the ZTL TeamSpeak 3 server and your roles should not auto assign. If they fail to assign, log back out and then in. If the roles fail to assign, please reach out to a ZTL staff member.</li>
            </ul>
        </div>
        <div class="col-sm-2">
        </div>
    </div>
    <hr>
    <center><p>If you have any additional questions, please contact either the ATM or DATM at <a href="atm@ztlartcc.org">atm@ztlartcc.org</a> or <a href="datm@ztlartcc.org">datm@ztlartcc.org</a> respectively.</p></center>
</div>

@endsection
