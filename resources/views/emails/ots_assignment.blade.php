@extends('layouts.email')

@section('content')
<p>Dear {{ $ins->fullname }},</p>
<p>You have been assigned an OTS. The details are listed below. Please reply to this email, or email the controller separately in order to schedule the OTS exam. If you have any questions, please contact the TA at <a href="mailto:ta@ztlartcc.org">ta@ztlartcc.org</a>.</p>
<br>
<p><b>Controller Name:</b> {{ $ots->controller_name }}</p>
<p><b>Position:</b> {{ $ots->position_name }}</p>
<p><b>Date of Recommendation:</b> {{ $ots->recommended_on }}</p>
<p><b>Recommending Mentor/Instructor:</b> {{ $ots->recommender_name }}</p>
<br>
<p>You can view more details from the OTS Center on the website. Please attempt to schedule the OTS in a timely manner.</p>
<br>
<p>Sincerely,</p>
<p>vZTL Training Administrator</p>
@endsection
