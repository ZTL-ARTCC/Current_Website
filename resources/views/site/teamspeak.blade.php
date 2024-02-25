@extends('layouts.master')

@section('title')
TeamSpeak
@endsection

@section('content')
@include('inc.header', ['title' => 'ZTL ARTCC Teamspeak Information', 'type' => 'external'])

<div class="container">
    <center><h1><a href="https://www.teamspeak.com/?tour=yes" target="_blank"><img width="50" src="/photos/ts_stacked.png"></a> vZTL ARTCC TeamSpeak Information <a href="https://www.teamspeak.com/?tour=yes" target="_blank"><img width="50" src="/photos/ts_stacked.png"></a></h1></center>
    <center><p><i>Thank you to TeamSpeak for providing us with our TeamSpeak license. Check them out at <a href="https://www.teamspeak.com/?tour=yes" target="_blank">https://www.teamspeak.com</a> for more information!</i></p></center>
    <hr>
    <center><h3>TeamSpeak is used for all training and all controller communications. All members are welcome to join us in the TeamSpeak. You can connect to the TeamSpeak server without a password, using the server:</h3></center>
    <center><h2><a href="ts3server://ts.ztlartcc.org?port=9987">ts.ztlartcc.org<br>Port: 9987</a></h2></center>
    <hr>
    <center><h2>TeamSpeak Rules:</h2></center>
    <div class="row">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-8">
            <ul>
                <li>All users must join the TeamSpeak using the name associated with their VATSIM account.</li>
                <li>Anonymous users will be kicked with a warning and banned upon reconnecting anonymously.</li>
                <li>TeamSpeak permissions are required to move within the server. Please contact a staff member to receive appropriate permissions. (If you are not a member of the VATUSA region, please provide your CID for rating verification).</li>
                <li>Although streaming while controlling is encouraged, you may not stream the audio from the ZTL TeamSpeak server for the privacy of other controllers. Streaming TeamSpeak audio requires the written permission of the ATM (Contact the ATM at <a href="atm@ztlartcc.org">atm@ztlartcc.org</a> for permission). Streaming audio without permission from the ATM may result in loss of TeamSpeak privileges.</li>
                <li>Controlling rooms are limited to controlling only. If a controller asks for you to leave, please do so.</li>
                <li>Use of the TeamSpeak is a privilege and can be revoked by a staff member at any time and for any reason. To appeal TeamSpeak bans, please contact the DATM at <a href="datm@ztlartcc.org">datm@ztlartcc.org</a>.</li>
            </ul>
            <p>Please review the <a href="https://www.ztlartcc.org/asset/TeamSpeakDiscordPolicy">TeamSpeak and Discord Policy</a> for the full list of rules prior to joining the TeamSpeak server.</p>
        </div>
        <div class="col-sm-2">
        </div>

            </div> 
<hr>
    <center><h2>Link your TeamSpeak Client UID to your ZTL Controller Profile to assign your roles:</h2></center>
    <div class="row">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-8">
            <ul>
                <li>Disconnect from the ZTL TeamSpeak server.</li>
				<li>In your TeamSpeak menu go to Tools -> Identities (Ctrl-I).</li>
                <li>Highlight & right click to copy your Unique ID to your clipboard.</li>
                <li>Proceed to your <a href="https://www.ztlartcc.org/dashboard/controllers/profile">ZTL Controller Dashboard</a> and scroll to the bottom to find your TeamSpeak UID field.</li>
                <li>Paste your TeamSpeak UUID into the text box and click the Save button.</li>
                <li>Log into the ZTL TeamSpeak server and your roles should not auto assign. If they fail to assign, log back out and then in. If the roles fail to assign, please reach out to a ZTL staff member.</li>
            </ul>
        </div>
        <div class="col-sm-2">
        </div>
    </div>
    <hr>
    <center><p>If you have any additional questions, please contact either the ATM or DATM at <a href="atm@ztlartcc.org">atm@ztlartcc.org</a> or <a href="datm@ztlartcc.org">datm@ztlartcc.org</a> respectively.</p></center>
</div>

@endsection
