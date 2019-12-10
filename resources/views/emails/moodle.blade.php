@extends('layouts.email')

@section('content')
<p>Please add user past and up to current ratings correct enrolment on Moodle</p>
<ul>
  <li><b>Controller Name:</b> {{user->fname}} {{user->lname}}</li></b>
  <li><b>Controller Email:</b< {{user->email}}<li></b><li>
  <li><b>Controller Rating:</b> {{user->rating_id}}</b></li>
 </ul>
