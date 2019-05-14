@extends('layouts.email')

@section('content')
<p>Dear {{ $visit->name }},</p>

<p>Your visitor application has been successfully submitted. You will be assigned a ZTL Visitor Exam that must be passed prior to your application being considered by our executive staff. This exam is more of a getting familiar with our Standard Operating Procedures. You will have seven (7) days to complete the exam and you must pass with a score of 80% or better. If you have any questions or concerns, please email the DATM at <a href="mailto:datm@ztlartcc.org">datm@ztlartcc.org</a>.</p>

<p>Best regards,</p>
<p>ZTL Visiting Staff</p>
@endsection
