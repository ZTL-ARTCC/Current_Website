@extends('layouts.master')

@section('title')
{{ $afld }} Ramp Status
@endsection

@section('content')

<span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        @if( $afld == 'ATL')
		<h2><center>Atlanta Hartsfield Jackson Int'l Airport (ATL) Ramp/Gate Status</center></h2>
		@endif
        @if( $afld == 'CLT')
		<h2><center>Charlotte Douglas Int'l Airport (CLT) Ramp/Gate Status</center></h2>
		@endif
        &nbsp;
    </div>
</span>
<div class="react-reveal">
<div class="container-fluid">
<div class="mb-4">
  <div id="map" class="center" style="width: 1200px; height: 600px"></div>
  <div class="clearfix">
    <div id="legend">
      <div class="legenditem"><span class="nofp"></span> No Flight Plan</div>
      <div class="legenditem"><span class="taxiarr"></span> Taxiing (Arr)</div>
      <div class="legenditem"><span class="taxidep"></span> Taxiing (Dep)</div>
    </div>
  </div>
</div>
</div>
</div>
<style>
.center {
  margin-left: auto;
  margin-right: auto;
}
#legend {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}
.legenditem {
  margin: 10px;
}
.legenditem span {
  border: 1px solid #ccc; float: left; width: 12px; height: 12px; margin: 2px;
}
.legenditem .nofp {
  background-color: #FF00FF;
}
.legenditem .parked {
  background-color: #FF0000;
}
.legenditem .taxiarr {
  background-color: #FFFF00;
}
.legenditem .taxidep {
  background-color: #00FF00;
}
</style>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo Config::get('google.api_key',''); ?>"></script>
<script type="text/javascript">
(function () {/*


 lastUpdated 2011 Google Inc.

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
*/
    var d = "prototype"; function e(a) { this.set("fontFamily", "sans-serif"); this.set("fontSize", 12); this.set("fontColor", "#000000"); this.set("strokeWeight", 4); this.set("strokeColor", "#ffffff"); this.set("align", "center"); this.set("zIndex", 1E3); this.setValues(a) } e.prototype = new google.maps.OverlayView; window.MapLabel = e; e[d].changed = function (a) { switch (a) { case "fontFamily": case "fontSize": case "fontColor": case "strokeWeight": case "strokeColor": case "align": case "text": return h(this); case "maxZoom": case "minZoom": case "position": return this.draw() } };
    function h(a) {
        var b = a.a; if (b) {
            var f = b.style; f.zIndex = a.get("zIndex"); var c = b.getContext("2d"); c.clearRect(0, 0, b.width, b.height); c.strokeStyle = a.get("strokeColor"); c.fillStyle = a.get("fontColor"); c.font = a.get("fontSize") + "px " + a.get("fontFamily"); var b = Number(a.get("strokeWeight")), g = a.get("text"); if (g) {
                if (b) c.lineWidth = b, c.strokeText(g, b, b); c.fillText(g, b, b); a: { c = c.measureText(g).width + b; switch (a.get("align")) { case "left": a = 0; break a; case "right": a = -c; break a }a = c / -2 } f.marginLeft = a + "px"; f.marginTop =
                    "-0.4em"
            }
        }
    } e[d].onAdd = function () { var a = this.a = document.createElement("canvas"); a.style.position = "absolute"; var b = a.getContext("2d"); b.lineJoin = "round"; b.textBaseline = "top"; h(this); (b = this.getPanes()) && b.mapPane.appendChild(a) }; e[d].onAdd = e[d].onAdd;
    e[d].draw = function () { var a = this.getProjection(); if (a && this.a) { var b = this.get("position"); if (b) { b = a.fromLatLngToDivPixel(b); a = this.a.style; a.top = b.y + "px"; a.left = b.x + "px"; var b = this.get("minZoom"), f = this.get("maxZoom"); if (b === void 0 && f === void 0) b = ""; else { var c = this.getMap(); c ? (c = c.getZoom(), b = c < b || c > f ? "hidden" : "") : b = "" } a.visibility = b } } }; e[d].draw = e[d].draw; e[d].onRemove = function () { var a = this.a; a && a.parentNode && a.parentNode.removeChild(a) }; e[d].onRemove = e[d].onRemove;
})()
</script>
<script type="text/javascript">
var lastUpdatedNode;
var markersArray = [];
var FDBArray = [];

// Used to georectify the base image
@if( $afld == 'ATL')
	const BOUNDS = {
		north: 33.65746,
		south: 33.61998,
		west: -84.4478,
		east: -84.4055
	};
	const CENTER = {
		lat: 33.64079, 
		lon: -84.43295
	};
@endif
@if( $afld == 'CLT')
	const BOUNDS = {
		north: 35.22770,
		south: 35.19999,
		west: -80.9676,
		east: -80.9287
	};
	const CENTER = {
		lat: 35.22006, 
		lon: -80.94410
	};
@endif

var AFLDimageBounds = new google.maps.LatLngBounds(
  new google.maps.LatLng(BOUNDS.south, BOUNDS.west),   //bottom left
  new google.maps.LatLng(BOUNDS.north, BOUNDS.east)     //top right
);

var styles = [
  {
    "stylers": [
      { "visibility": "off" }
    ]
  }
];

var styledMap = new google.maps.StyledMapType(styles, {name: "Styled Map"});
// Imports the base map
var i = Math.floor(Math.random() * 10001);
var historicalOverlay = new google.maps.GroundOverlay(
  @if( $afld == 'ATL')
  'https://www.ztlartcc.org/photos/KATL_Diagram.PNG?i=' + i, // Add random number parameter as cachebuster? Math.floor(Math.random() * 101);
  @endif
  @if( $afld == 'CLT')
  'https://www.ztlartcc.org/photos/KCLT_Diagram.PNG',
  @endif
  AFLDimageBounds
);

// Init the map onload
window.onload = load();

function load() {
  var map = new google.maps.Map(document.getElementById("map"), {
	center: new google.maps.LatLng(CENTER.lat, CENTER.lon),
    disableDefaultUI: true,
    panControl: true,
    zoomControl: true,
    zoom: 16,          //16
    minZoom: 10,       //16
	restriction: {
		latLngBounds: BOUNDS,
		strictBounds: false,
	},
    mapTypeControlOptions: {
      mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
    }
  });

  map.mapTypes.set('map_style', styledMap);
  map.setMapTypeId('map_style');
  var logoControlDiv = document.createElement('DIV');
  var logoControl = new MyLogoControl(logoControlDiv);
  logoControlDiv.index = 0; // used for ordering
  map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(logoControlDiv);

  loadingDiv = document.createElement("div")
  loadingDiv.id = "map-loading"
  loadingDiv.style.fontSize = "32px"
  loadingDiv.style.fontWeight = "Bold"
  loadingDiv.style.fontFamily = "Arial, sans-serif"
  loadingDiv.style.margin = "0 2px 2px 0"
  loadingDiv.style.color = "red"
  loadingDiv.style.whiteSpace = "nowrap"
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(loadingDiv)
  loadingDiv.innerHTML = "Loading... please wait"

  lastUpdatedDiv = document.createElement("div")
  lastUpdatedDiv.id = "map-lastUpdated"
  lastUpdatedDiv.style.fontSize = "15px"
  lastUpdatedDiv.style.fontFamily = "Arial, sans-serif"
  lastUpdatedDiv.style.margin = "0 2px 2px 0"
  lastUpdatedDiv.style.color = "red"
  lastUpdatedDiv.style.whiteSpace = "nowrap"
  map.controls[google.maps.ControlPosition.TOP_RIGHT].push(lastUpdatedDiv)

  var infoWindow = new google.maps.InfoWindow;

  historicalOverlay.setMap(map);
  historicalOverlay.setOpacity(.4);

  setInterval(function(){
    downloadUrl("https://ids.ztlartcc.org/gatedisplay_xml.php?afld=K{{ $afld }}", function(data) {
      var xml = data.responseXML;
      markers = xml.documentElement.getElementsByTagName("marker");
      DateMod = xml.documentElement.getElementsByTagName("datemod");
      map.clearOverlays();
      for (var i = 0; i < markers.length; i++) {
        var name = markers[i].getAttribute("name");
        var dest = markers[i].getAttribute("dest");
        if (name == "DATEMODED") {
          lastUpdatedDiv.innerHTML = "Last Updated: " +dest;
        } else {
          var type = markers[i].getAttribute("type");
          var ACType = markers[i].getAttribute("ACType");
          var flightdata = markers[i].getAttribute("flightdata");
          var HDG = markers[i].getAttribute("HDG");
          var PColor = markers[i].getAttribute("PColor");
          var point = new google.maps.LatLng(
            parseFloat(markers[i].getAttribute("lat")),
            parseFloat(markers[i].getAttribute("lng")));
            var html = "<b>" + name + "</b> <br/>" + dest + "<br/>" + flightdata + "<br/>Hdg: " + HDG;
            HDG -= 180;  //for some reason need to orient the plane off 180 deg
            var marker = new google.maps.Marker({
              map: map,
              position: point,
              icon: {
                anchor: new google.maps.Point(75,75),
                path: 'M70 115 c0 -18 -9 -31 -30 -43 -16 -9 -30 -21 -30 -25 0 -5 14 -5 31 -2 30 7 31 6 25 -19 -5 -22 -3 -26 14 -26 17 0 19 4 14 26 -6 25 -5 26 25 19 17 -3 31 -3 31 2 0 4 -14 16 -30 25 -21 12 -30 25 -30 43 0 14 -4 25 -10 25 -5 0 -10 -11 -10 -25z',
                fillOpacity: 1,
                fillColor: PColor,
                strokeWeight: 0,
                scale: 0.1,
                rotation: HDG
              }
            });

            fLat = parseFloat(markers[i].getAttribute("lat"))+.0002;
            fLon = parseFloat(markers[i].getAttribute("lng"))+.0002;
            var point = new google.maps.LatLng(fLat, fLon);
            FDBInfo = name; // +" " +ACType;
            var FDBLabel = new MapLabel({
              text: FDBInfo,
              position: point,
              map: map,
              fontSize: 11,
              strokeWeight: 0,
              fontColor: '#00FF00',
              align: 'left'
            });

            markersArray.push(marker);
            FDBArray.push(FDBLabel);
            bindInfoWindow(marker, map, infoWindow, html);
          }
        }
		loadingDiv.remove(); // Remove the please wait notice after loading is complete
      })},10000);
  }

  function bindInfoWindow(marker, map, infoWindow, html) {
    google.maps.event.addListener(marker, 'mouseover', function() {
      infoWindow.setContent(html);
      infoWindow.open(map, marker);
    });
  }

  google.maps.Map.prototype.clearOverlays = function() {
    for (var i = 0; i < markersArray.length; i++ ) {
      markersArray[i].setMap(null);
    }
    for (var j = 0; j < FDBArray.length; j++ ) {
      FDBArray[j].setMap(null);
    }
  }

  function downloadUrl(url, callback) {
    var request = window.ActiveXObject ?
    new ActiveXObject('Microsoft.XMLHTTP') :
    new XMLHttpRequest;
    request.onreadystatechange = function() {
      if (request.readyState == 4) {
        callback(request, request.status);
      }
    };
    request.open('GET', url, true);
    request.send(null);
  }

  function MyLogoControl(controlDiv) {
      controlDiv.style.padding = '5px';
      var logo = document.createElement('IMG');
      logo.src = 'https://www.ztlartcc.org/photos/logo.png';
	  logo.setAttribute('height', '50px');
      logo.style.cursor = 'pointer';
      controlDiv.appendChild(logo);
      google.maps.event.addDomListener(logo, 'click', function() {
          window.location = 'https://ztlartcc.org/';
      });
  }
</script>
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
