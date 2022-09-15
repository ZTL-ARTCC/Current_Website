@extends('layouts.email')

@section('content')
<p>Dear {{ $visit->name }},</p>

<p>Your visitor application has been successfully submitted. In the next 24 hours, you will be assigned the ZTL Visitor Exam through the 
    VATUSA Academy. Please visit the <a href="https://academy.vatusa.net/">VATUSA Academy</a> and under "Academy Courses" select "All 
    Courses". You should then see the ZTL Visiting Exams course once it has been assigned.</p>
<p>The ZTL Visitor Exam will ensure you have the skills required to control at ZTL. Certain questions will require you to reference various 
    SOPs and LOAs that can be found on the <a href="https://www.ztlartcc.org/controllers/files">ZTL Website</a>. Some questions will ensure 
    you understand the rules for being a member at ZTL. The answers to these questions can be found in our Facility Administrative Policy.</p>
<p>You will have seven (7) days to complete the exams and you must pass each with a score of 80% or better. If you have any questions or 
    concerns, please email the DATM at <a href="mailto:datm@ztlartcc.org">datm@ztlartcc.org</a>.</p>
<p>Best regards,</p>
<p>ZTL Visiting Staff</p>
@endsection
