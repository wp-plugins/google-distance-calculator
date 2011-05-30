// JavaScript Document

var directionDisplay;
  var directionsService = new google.maps.DirectionsService();
  var map;

  function initialize(lat,lng) {
    directionsDisplay = new google.maps.DirectionsRenderer();
    //var location = new google.maps.LatLng(43.6525, -79.3816667);
	var location = new google.maps.LatLng(lat, lng);
    var myOptions = {
      zoom:7,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      center: location
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    directionsDisplay.setMap(map);
  }

  function calcRoute(from,to){
	var start = from;
    var end = to;
    var request = {
        origin:start,
        destination:end,
        travelMode: google.maps.DirectionsTravelMode.DRIVING,
	unitSystem: google.maps.DirectionsUnitSystem.METRIC
    };
    directionsService.route(request, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);

		distance = response.routes[0].legs[0].distance.text;
		time_taken = response.routes[0].legs[0].duration.text;

		//document.getElementById('distance').innerHTML = "The distance between "+response.routes[0].legs[0].start_address+" and "+response.routes[0].legs[0].end_address+" is "+dist;
		document.getElementById('distance').innerHTML = '<div class="distance-inner">'+ "The distance between <em>"+from+"</em> and <em>"+to+"</em>: <strong>"+distance+"</strong><br/>Time take to travel: <strong>"+time_taken+"</strong></div>";

		steps = "<ul>";
		var myRoute = response.routes[0].legs[0];
		for (var i = 0; i < myRoute.steps.length; i++) {
		 steps += "<li>" + myRoute.steps[i].instructions + "</li>";
		}
		steps += "</ul>";
		document.getElementById('steps').innerHTML = '<div class="steps-inner"><h2>Driving directions to '+response.routes[0].legs[0].end_address+'</h2>'+steps+'</div>';
      }
	  else{
		document.getElementById('distance').innerHTML = '<span class="gdc-error">Google Map could not be created for the entered parameters. Please be specific while providing the destination location.</span>';
	  }
    });
  }

//window.onload=function(){initialize();}