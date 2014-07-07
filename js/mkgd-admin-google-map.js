var autocomplete;
function initialize() {
  var default_location = document.getElementById('autocomplete');
  autocomplete = new google.maps.places.Autocomplete(default_location, {types: ['geocode']});
  google.maps.event.addListener(autocomplete, 'place_changed', function() {    
  });
}
window.onload = initialize();