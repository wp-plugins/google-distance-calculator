
// Sets a listener on a radio button to change the filter type on Places
var defaultBounds = new google.maps.LatLngBounds(
  new google.maps.LatLng(-33.8902, 151.1759),
  new google.maps.LatLng(-33.8474, 151.2631));
  
var options = {
  bounds: defaultBounds,  
  types: []
};

var origin = document.getElementById('origin');
autocomplete = new google.maps.places.Autocomplete(origin, options);

var destination = document.getElementById('destination');
autocomplete = new google.maps.places.Autocomplete(destination, options);


//================= Google Direction Map =====================================

var directionDisplay;
var directionsService = new google.maps.DirectionsService();
var map;

/*function initialize() {
  directionsDisplay = new google.maps.DirectionsRenderer();
  var chicago = new google.maps.LatLng(41.850033, -87.6500523);
  var mapOptions = {
    zoom:7,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    center: chicago
  }
  map = new google.maps.Map(document.getElementById('mkgd-map-canvas'), mapOptions);
  directionsDisplay.setMap(map);
}*/

function calcRoute() {
  var start = document.getElementById('origin').value;
  var end = document.getElementById('destination').value;
  var request = {
    origin:start,
    destination:end,
    travelMode: google.maps.DirectionsTravelMode.DRIVING
  };
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
    }
  });
  return false;
}

window.onload =  initialize();