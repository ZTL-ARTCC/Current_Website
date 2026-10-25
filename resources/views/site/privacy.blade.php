@extends('layouts.master')

@section('title')
Privacy
@endsection

@section('content')
@include('inc.header', ['title' => '<center>ZTL ARTCC Privacy Policy & Terms and Conditions</center>', 'type' => 'external'])

<div class="react-reveal">
@include('site.privacy_policy')
</div>
<footer>
<div class="d-flex justify-content-center mb-5">
<div class="position-unset col-xl-6 col-10">
<div class="fade position-unset m-0 undefined alert alert-primary show" role="alert">
<table>
<tbody>
<tr>
<td class="pb-2 pb-md-1">&nbsp;</td>
<td class="pb-2 pb-md-1">
<h5 class="mb-0">Disclaimer!</h5>
</td>
</tr>
<tr>
<td class="d-none d-md-table">&nbsp;</td>
<td colspan="2">
<p class="m-0">All information on this website is for flight simulation use only and is not to be used for real world navigation or flight. This site is not affiliated with ICAO, the FAA, the actual Atlanta ARTCC, or any other real world aerospace entity.</p>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</footer>
@endsection
