// JavaScript Document

var map; var directionsDisplay;

// set map options
var myLatLng = {
			lat:53.8108,
			lng:-1.7626
		};

var mapOptions = {
			center: myLatLng,
			zoom: 9,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			disableDefaultUI: true
};

// create autocomplete objects
var input1 = document.getElementById("departure");
var input2 = document.getElementById("destination");

var input3 = document.getElementById("departure2");
var input4 = document.getElementById("destination2");


var options = {
	types: ['(cities)']
};

var autocomplete1 = new google.maps.places.Autocomplete(input1,options);
var autocomplete2 = new google.maps.places.Autocomplete(input2,options);
var autocomplete3 = new google.maps.places.Autocomplete(input3,options);
var autocomplete4 = new google.maps.places.Autocomplete(input4,options);

// create a DirectionsService object to use the route method
var directionsService = new google.maps.DirectionsService();

// onload, addDomListener when we load a page we lounch initialize function :
google.maps.event.addDomListener(window,'load',initialize);

// Calculate the route when we selecting one of Autocomplete
google.maps.event.addListener(autocomplete1,'place_changed', calculateRoute);
google.maps.event.addListener(autocomplete2,'place_changed', calculateRoute);


// FUNCTIONS

// initialize draw map in the googleMap div
function initialize(){
	// create a DirectionsRenderer Object which will be used to display the route
	directionsDisplay = new google.maps.DirectionsRenderer(); 
	
	// create map
	map = new google.maps.Map(document.getElementById("googleMap"),mapOptions);
	
	// bind the DirectionsRenderer to the map
	directionsDisplay.setMap(map);
}

// Calculate Route function: the route when we selecting one of Autocomplete
function calculateRoute(){
	var start = $('#departure').val();
	var end = $('#destination').val();
	
	var request = {
		origin: start,
		destination: end,
		travelMode: google.maps.DirectionsTravelMode.DRIVING
	};
	
	// check if departure and destination input filled
	if(start && end){
		directionsService.route(request, function(response,status){
			if(status == google.maps.DirectionsStatus.OK){
				directionsDisplay.setDirections(response);
			}
		});
	}else{
		initialize();
	}
}
