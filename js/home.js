
/*
Autocomplete for driver address
*/
function initializeDriver() {
	var input = document.getElementById('DriverLocn');
	var options = {
		componentRestrictions: {country: 'au'}
	};
	
	var autocomplete = new google.maps.places.Autocomplete(input, options);
	
	google.maps.event.addListener(autocomplete, 'place_changed', function () {
		var place = autocomplete.getPlace();
		var lat = place.geometry.location.lat();
		var lon = place.geometry.location.lng();
		document.getElementById('driverLat').value = lat;
        document.getElementById('driverLng').value = lon;
	});
}

/*
Autocomplete for Customer (the one placing an order) address
Sends the latitude and longitude of the user to AJAX to check if there are drivers
who will be willing to deliver to that location. home_ajax.php then creates a map
populated with restaurants that the customer can order from.
*/
function initializeUser() {
	var input = document.getElementById('UserLocn');
	var options = {
		componentRestrictions: {country: 'au'}
	};
	
	var autocomplete = new google.maps.places.Autocomplete(input, options);
	
	google.maps.event.addListener(autocomplete, 'place_changed', function () {
		
		var place = autocomplete.getPlace();
		var lat = place.geometry.location.lat();
		var lon = place.geometry.location.lng();
		document.getElementById('userLat').value = lat;
        document.getElementById('userLng').value = lon;
		// The variable that makes Ajax possible!
		var ajaxRequest = new XMLHttpRequest();  
		
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				document.getElementById("txtHint").innerHTML = ajaxRequest.responseText;
			}
		}
		var queryString = "?lat=" + lat + "&lon=" + lon;
		ajaxRequest.open("GET", "home_ajax.php" + queryString, true);
		ajaxRequest.send(null); 
	});
}

// Variables needed to make the map
var map;
var service;
var infowindow;
var markers = [];

/*
Initialise google map and perform nearby search 
for each driver who'd be willing to deliver to the user's 
location. Centre of the search is the driver's lat and lng
and the radius is their radius of delivery.
*/
function initializeMap() {
  var userLat = document.getElementById('userLat').value;
  var userLng = document.getElementById('userLng').value;  
  var userloc = new google.maps.LatLng(userLat, userLng);

  map = new google.maps.Map(document.getElementById('map'), {
      center: userloc,
      zoom: 15
    });
   
 infowindow = new google.maps.InfoWindow();
 service = new google.maps.places.PlacesService(map);
 
 var num_drivers = document.getElementsByName("num_driver")[0].value;

 //Initialise min and max latitude and longitude to get bounds		
 if(num_drivers != 0){
	 var min_driver_lat = document.getElementsByName("drivers[0][1]")[0].value; 
	 var max_driver_lat = document.getElementsByName("drivers[0][1]")[0].value; 
	 var min_driver_lon = document.getElementsByName("drivers[0][2]")[0].value; 
	 var max_driver_lon = document.getElementsByName("drivers[0][2]")[0].value;
 }else{
	 var min_driver_lat = userLat; 
	 var max_driver_lat = userLat; 
	 var min_driver_lon = userLng; 
	 var max_driver_lon = userLng;
 }
 
 for(var i = 0; i < num_drivers; i++){
	var driver_lat = document.getElementsByName("drivers["+i+"][1]")[0].value;
	if(driver_lat < min_driver_lat){min_driver_lat = driver_lat;}
	if(driver_lat > max_driver_lat){max_driver_lat = driver_lat;}
	
	var driver_lon = document.getElementsByName("drivers["+i+"][2]")[0].value;
	if(driver_lon < min_driver_lon){min_driver_lon = driver_lon;}
	if(driver_lon > max_driver_lon){max_driver_lon = driver_lon;}
	
	var driver_rad = document.getElementsByName("drivers["+i+"][3]")[0].value * 1000;
	var driver_loc = new google.maps.LatLng(driver_lat, driver_lon);
	var request = {
    	location: driver_loc,
    	radius: driver_rad,
    	types: ['restaurant']
  	};
    service.nearbySearch(request, callback);
 }

  var defaultBounds = new google.maps.LatLngBounds(
      new google.maps.LatLng(min_driver_lat, min_driver_lon),
      new google.maps.LatLng(max_driver_lat, max_driver_lon));
  map.fitBounds(defaultBounds);
 
 	var input = document.getElementById('search');	
	var searchBox = new google.maps.places.SearchBox(input);
 	
 	//Get place(s) based on what was entered in the search box.
	google.maps.event.addListener(searchBox, 'places_changed', function() {
		var places = searchBox.getPlaces();
    	var bounds = new google.maps.LatLngBounds();

		if(places.length > 0){ 
			clearMarkers();
			for (var i = 0, place; place = places[i]; i++){
	
				createMarker(place);
      			bounds.extend(place.geometry.location);
			}
		}		
		    map.fitBounds(bounds);
	
	});
	
	google.maps.event.addListener(map, 'bounds_changed', function() {
    var bounds = map.getBounds();
    searchBox.setBounds(bounds);
  });
	
}

function callback(results, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {
      var place = results[i];
      createMarker(place, 0);
    }
  }
}

/*
Create marker and display infowindow
*/
function createMarker(place) {
  var placeLoc = place.geometry.location;
  var content = contentWindow(place);
  var marker = new google.maps.Marker({
    map: map,
    title: place.name,
    position: place.geometry.location
  });
  markers.push(marker);
  
  
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent(content);
    infowindow.open(map, this);
    document.getElementById('RestName').value = place.name;
    if(place.formatted_address){
    	document.getElementById('RestLocn').value = place.formatted_address;
    }else{
    	document.getElementById('RestLocn').value = place.vicinity;
    }    
    document.getElementById('restLat').value = place.geometry.location.lat();
    document.getElementById('restLng').value = place.geometry.location.lng();

  });
  
}


function contentWindow(place) {
	var content = '';
	content += '<table>';
	content += '<tr class="iw_table_row">';
	content += '<td style="text-align: right"><img class="hotelIcon" src="' + place.icon + '"/></td>';
	content += '<td><b><a href="' + place.url + '">' + place.name + '</a></b></td></tr>';
	
	if(place.formatted_address){
	content += '<tr class="iw_table_row"><td class="iw_attribute_name">Address:</td><td>' + place.formatted_address + '</td></tr>';
	}else{
	content += '<tr class="iw_table_row"><td class="iw_attribute_name">Address:</td><td>' + place.vicinity + '</td></tr>';
	}
	
	if (place.formatted_phone_number) {
		content += '<tr class="iw_table_row"><td class="iw_attribute_name">Telephone:</td><td>' + place.formatted_phone_number + '</td></tr>';
	}
	
	if (place.rating) {
		var ratingHtml = '';
		for (var i = 0; i < 5; i++) {
			if (place.rating < (i + 0.5)) {
				ratingHtml += '&#10025;';
			} else {
				ratingHtml += '&#10029;';
			}
		}
		content += '<tr class="iw_table_row"><td class="iw_attribute_name">Rating:</td><td><span id="rating">' + ratingHtml + '</span></td></tr>';
	}	
	
	content += '</table>';
	return content;
}
/*
Clear all markers that are currently on
the map
*/
function clearMarkers() {
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers = [];
}

google.maps.event.addDomListener(window, 'load', initializeUser);
google.maps.event.addDomListener(window, 'load', initializeDriver);
