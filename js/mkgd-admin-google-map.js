var autocomplete_1, autocomplete_2;
function initialize() {
  // Start Location
  var start_location = document.getElementById('autocomplete_1');
  autocomplete_1 = new google.maps.places.Autocomplete(start_location, {types: ['geocode']});
  google.maps.event.addListener(autocomplete_1, 'place_changed', function() {
  });
  // Destination Location
  var destination_location = document.getElementById('autocomplete_2');
  autocomplete_2 = new google.maps.places.Autocomplete(destination_location, {types: ['geocode']});
  google.maps.event.addListener(autocomplete_2, 'place_changed', function() {
  });
}
window.onload = initialize();