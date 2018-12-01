@extends('layouts.email')

@section('content')
<p>A new bug has been reported.</p>
<br>
<p><b>Details:</b></p>
<p><b>URL:</b> {{ $url }}</p>
<p><b>Error:</b> {{ $error }}</p>
<p><b>Description:</b></p>
<p>{!! nl2br($desc) !!}</p>
@endsection
