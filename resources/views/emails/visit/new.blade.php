@extends('layouts.email')

@section('content')
<p>Dear {{ $visit->name }},</p>

<p>Your visitor application has been successfully submitted. In the next 24 hours, you will be assigned two exams via the VATUSA Exam Center. The first part of the GRP check will ensure you have the skills required to control at ZTL. Some questions will require you to reference various SOPs and LOAs that can be found on the <a href="www.ztlartcc.org/dashboard/controllers/files">ZTL files page.</a></p>
<p>The second part of the GRP check will ensure you understand the rules for being a member at ZTL. The answers to these questions can be found in our Facility Administrative Policy on the files page.</p>
<p>You will have seven (7) days to complete the exams and you must pass each with a score of 80% or better. If you have any questions or concerns, please email the DATM at <a href="mailto:datm@ztlartcc.org">datm@ztlartcc.org</a>.</p>

<p>Best regards,</p>
<p>ZTL Visiting Staff</p>
@endsection
