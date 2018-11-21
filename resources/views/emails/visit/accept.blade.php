@extends('layouts.email')
@section('content')
    <p>Dear {{ $visitor->name }},</p>

    <p>Congratulations, you have been accepted as a visitor in Atlanta.</p><br>
    <p>You can find all the information for the various facilities within the ARTCC under the files page on the website, <a href="http://www.ztlartcc.org">www.ztlartcc.org</a>.</p><br>
    <p>Teamspeak 3 is used for all controller communications. The IP for the server we use is ts.ztlartcc.org and there is no password.</p><br>
    <p>All visitors are certified to control minor fields through their current rating. It is highly recommended that you review the SOPs and LOAs located on the website prior to logging onto the network. Although it is not required, it is recommended to schedule a session with a mentor or instructor to go over the procedures in Charlotte.</p><br>
    <p>Once again, congratulations on being accepted as a visitor in Atlanta and we hope to see you on the network soon! If you have any questions, feel free to email the DATM at <a href="mailto:datm@ztlartcc.org">datm@ztlartcc.org</a>.</p><br>

    <p>Best regards,</p>
    <p>ZTL Visiting Staff</p>
@endsection
