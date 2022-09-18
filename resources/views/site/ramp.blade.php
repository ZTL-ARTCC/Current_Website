@extends('layouts.master')

@section('title')
{{ $afld }} Ramp Status
@endsection

@push('custom_header')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<link rel="stylesheet" href="{{ asset('css/pilots_guide.css') }}" />
@endpush

@section('content')
<span class="border border-light" style="background-color:#F0F0F0">
  <div class="container">
    &nbsp;
    @if( $afld == 'ATL')
    <h2>
      <center>Atlanta Hartsfield Jackson Int'l Airport (ATL) Ramp/Gate Status</center>
    </h2>
    @endif
    @if( $afld == 'CLT')
    <h2>
      <center>Charlotte Douglas Int'l Airport (CLT) Ramp/Gate Status</center>
    </h2>
    @endif
    &nbsp;
  </div>
</span>
<div class="container">
  <div style="width:100%; height:70vh">
    <div id="map" style="position:relative; height:100%; max-width:100%; max-height:100%; cursor:crosshair"></div>
  </div>
  <div id="legend">
    <div class="legenditem"><span class="taxiarr"></span> Arrival</div>
    <div class="legenditem"><span class="taxidep"></span> Departure</div>
    <div class="legenditem"><span class="nofp"></span> Other</div>
  </div>
</div>
<script>
  @if($afld == 'ATL')
  const centroid = [33.64079, -84.43295];
  const maxLatLon = [33.66, -84.39];
  const minLatLon = [33.61, -84.46];
  @endif
  @if($afld == 'CLT')
  const centroid = [35.22006, -80.94410];
  const maxLatLon = [35.22770, -80.9287];
  const minLatLon = [35.19999, -80.9676];
  @endif
</script>
{{Html::script(asset('js/pilots_guide.js'))}}
@endsection