@extends('layouts.email')

@section('content')
<p>The following solo certification has expired and no OTS has taken place for the controller for the desired rating.</p>
<br>
<p><b>CID:</b> {{ $cert->cid }}</p>
<p><b>Position:</b> {{ $cert->pos_txt }}</b></p>
@endsection
