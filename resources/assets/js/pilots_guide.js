var map = L.map("map").setView(centroid, 15);
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
  subdomains: ["a", "b", "c"],
  maxZoom: 18,
  minZoom: 15,
}).addTo(map);

var planeLayer = new L.LayerGroup();
planeLayer.addTo(map);
window.planeLayer = planeLayer;

function resizeMap() {
  setTimeout(function () {
    map.invalidateSize();
    updatePlanes();
  }, 500);
}

window.resizeMap = resizeMap;

function updatePlanes() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      resp = JSON.parse(this.responseText);
      planeLayer.clearLayers();
      resp.data.forEach((plane) => {
        if (
          plane.lat < maxLatLon[0] &&
          plane.lon < maxLatLon[1] &&
          plane.lat > minLatLon[0] &&
          plane.lon > minLatLon[1]
        ) {
          // Enforce bounding box and airborne logic
          createPlane(
            plane.lat,
            plane.lon,
            plane.hdg,
            plane.callsign,
            plane.type,
            plane.dep,
            plane.arr
          );
        }
      });
    }
  };
  xhttp.open(
    "GET",
    "https://ids.ztlartcc.org/asx/vatusa_api_fetch_aircraft.php",
    true
  );
  xhttp.send();
}

window.updatePlanes = updatePlanes;

function createPlane(lat, lon, hdg, cs, actype, dep, arr, sel = false) {
  var color = null;
  if (arr == "K{{ $afld }}") color = "red";
  else if (dep == "K{{ $afld }}") color = "green";
  else color = "purple";
  var myIcon = L.divIcon({
    html:
      '<img src="https://ids.ztlartcc.org/asx/planes/' +
      color +
      '.png" class="rotate-' +
      hdg +
      '">',
    className: "trackedAircraft",
  });
  lat = parseFloat(lat);
  lon = parseFloat(lon);
  var marker = L.marker([lat, lon], {
    icon: myIcon,
  }).bindPopup(
    '<span class="row1">' +
      cs +
      '</span><br> \
      <span class="row2">' +
      dep +
      " - " +
      arr +
      '</span><br> \
      <span class="row3">' +
      actype +
      "</span>"
  );
  marker.on("mouseover", function (e) {
    this.openPopup();
  });
  marker.on("mouseout", function (e) {
    this.closePopup();
  });
  this.planeLayer.addLayer(marker);
}

setInterval(function () {
  updatePlanes();
}, 1 * 15 * 1000); // Every 15 seconds

var southWest = L.latLng(minLatLon[0], minLatLon[1]),
  northEast = L.latLng(maxLatLon[0], maxLatLon[1]);
var bounds = L.latLngBounds(southWest, northEast);

map.setMaxBounds(bounds);
map.on("drag", function () {
  map.panInsideBounds(bounds, {
    animate: false,
  });
});
