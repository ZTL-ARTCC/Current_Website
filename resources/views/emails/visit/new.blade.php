@extends('layouts.email')

@section('content')
<p>Dear {{ $visitor->name }} (CID {{ $visitor->cid }}),</p>
<p>Thank you for your interest in the Atlanta ARTCC. Your visiting application has been 
    successfully submitted. You have automatically been assigned to our ZTL Visitor Exam course 
    in the ZTL ARTCC Center of Knowledge (ZACK). Please <a href="https://learn.ztlartcc.org">
    visit the ZACK</a> and under “My Courses,” select “
    <a href="https://learn.ztlartcc.org/course/view.php?id=3">ZTL Visiting Exams</a>.” Please review 
    the documents and take the visiting controller exam.</p>
<p>The ZTL Visitor Exam will ensure you have the skills required to control at ZTL. Certain 
    questions will require you to reference various SOPs and LOAs that can be found on the 
    <a href="https://ztlartcc.org">ZTL Website</a>. Some questions will ensure you understand the 
    rules for being a member at ZTL. The answers to these questions can be found in our Facility 
    Administrative Policy.</p>
<p>You will have seven (7) days to complete the exams and you must pass each with a score of 80% 
    or better. If you have any questions or concerns, please email the DATM at <a href="mailto:{{config('artcc.email_datm')}}">{{config('artcc.email_datm')}}</a>.</p>
<p>Best regards,</p>
<p>ZTL Staff</p>
@endsection
