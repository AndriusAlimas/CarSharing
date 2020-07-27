$(document).ready(function () {
	// we need this variables outside the functions, to use it later on
	var data, departureLongitude, departureLatitude, destinationLongitude, destinationLatitude, trip;
	
	// when we load the page get all trips with that user
	getTrips();

	// create a geocoder object to use geocode
	var geocoder = new google.maps.Geocoder();
		// Fix Map
		$("#addTripModal").on('shown.bs.modal',function(){
			google.maps.event.trigger(map,"resize");
		});
	
	// Hide all date-time-checkbox inputs on add trip modal
	$('.regular').hide(); $('.one-off').hide();
	
	// Hide all date-time-checkbox inputs on edit trip modal
	$('.regular2').hide(); $('.one-off2').hide();
	
//	add trip modal radio if checked or not show calendar
	var myRadio = $('input[name="regular"]');
	
	myRadio.click(function(){
		if($(this).is(':checked')){
			if($(this).val() == "Y"){
				$('.one-off').hide(); $('.regular').show(); 
			}else{
				$('.regular').hide(); $('.one-off').show();
			}
		}
	});

//	edit trip modal radio if checked or not show calendar
	var myRadio2 = $('input[name="regular2"]');
	
	myRadio2.click(function(){
		if($(this).is(':checked')){
			if($(this).val() == "Y"){
				$('.one-off2').hide(); $('.regular2').show(); 
			}else{
				$('.regular2').hide(); $('.one-off2').show();
			}
		}
	});	
	
	// Calendar for Add trip and Edit trip
	$('input[name="date"], input[name="date2"]').datepicker({
		numberOfMonths: 1,
		showAnim: "fadeIn",
		dateFormat: "D d M, yy",
		minDate: +1,
		maxDate: "+12M",
		showWeek: true
	});
	
	// Click on Create Trip Button
	$("#addTripForm").submit(function(event){
		// show spinner
		  $("#spinner").show();
		
		// hide error,success message
			$("#addTripMessage").hide();
		
		event.preventDefault();
		
		data = $(this).serializeArray(); // get all inputs from that form
		
		getAddTripDepartureCoordinates();
	});
	
// FUNCTIONS
// this function get departure coordinates which is longitude and latitude, when user press Add Trip button, and then push this to serialiazed array where later we can retrieve this coordinates and push to database
function getAddTripDepartureCoordinates(){
		geocoder.geocode(
			{
			'address' : document.getElementById("departure").value
		},
			function(results, status){
				if(status == google.maps.GeocoderStatus.OK){
				     departureLongitude = results[0].geometry.location.lng();
				     departureLatitude = results[0].geometry.location.lat();
					 data.push(
						 {
							 name: 'departureLongitude', value: departureLongitude
						 });
						 
					 data.push(
						 {
							 name: 'departureLatitude', value: departureLatitude
						 });
						 
						getAddTripDestinationCoordinates();
				   }else{
				   		getAddTripDestinationCoordinates();
				   }
			}
	);
	}
	
	// this function get destination coordinates which is longitude and latitude, when user press Add Trip button, and then push this to serialiazed array where later we can retrieve this coordinates and push to database
function getAddTripDestinationCoordinates(){
		geocoder.geocode(
			{
			'address' : document.getElementById("destination").value
		},
			function(results, status){
				if(status == google.maps.GeocoderStatus.OK){
				     destinationLongitude = results[0].geometry.location.lng();
				     destinationLatitude = results[0].geometry.location.lat();
					 data.push(
						 {
							 name: 'destinationLongitude', value: departureLongitude
						 });
						 
					 data.push(
						 {
							 name: 'destinationLatitude', value: destinationLatitude
						 });
						 submitAddTripRequest();
				   }else{
				   		submitAddTripRequest();
				   }
			}
	);
	}
	
function submitAddTripRequest(){
		
		  // send AJAX call to addtrips.php file
        $.ajax({
            url: "addtrips.php",
			data: data, // our variable data
            type: "POST",
             // AJAX Call successful: show error or success message
            success: function(returnedData){
				// hide spinner
					$("#spinner").hide();
                if(returnedData){
					 // errors message
					 $("#addTripMessage").html(returnedData);
					
					// show animation for apearing messages
					$("#addTripMessage").slideDown();
				}else{
					// delete previous error message:
					$("#addTripMessage").attr("class",'alert');
					$("#addTripMessage").html("");
					
					// hide modal
					$("#addTripModal").modal('hide');
					
					// reset form
					$('#addTripForm')[0].reset();
					
					// hide regular and one-off element
					$(".regular").hide();
					$(".one-off").hide();
					
					// reset google display
					directionsDisplay.setMap(null);
				
					// load trips
					getTrips();
				}
            },
            error: function(){
				// hide spinner
					$("#spinner").hide();
				
                 // AJAX Call fails: show Ajax Call error
                $("#addTripMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
				
					// show animation for apearing messages
					$("#addTripMessage").slideDown();
            }
        });
}

// get trips	
function getTrips(){
	 // show spinner
		$("#spinner").show();
		  // send AJAX call to getTrips.php file
        $.ajax({
            url: "getTrips.php",
			
             // AJAX Call successful: show error or success message
            success: function(returnedData){
		     // hide spinner
				$("#spinner").hide();
				$("#myTrips").hide();
				
                if(returnedData){
					 $("#myTrips").html(returnedData);
				}
				
				// show with fade In animation
				$("#myTrips").fadeIn();
            },
            error: function(){
				// hide spinner
				$("#spinner").hide();
				$("#myTrips").hide();
                 // AJAX Call fails: show Ajax Call error
                $("#myTrips").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
				
				// show with fade In animation
				$("#myTrips").fadeIn();
            }
        });
}	

// format modal how was before
function formatModal(){
		$('#departure2').val(trip['departure']);
		$('#destination2').val(trip['destination']);
		$('#price2').val(trip['price']);
		$('#seatsAvailable2').val(trip['seatsavailable']);
		
		if(trip['regular'] == "Y"){
		    $('#yes2').prop('checked', true);
		    
			// if trip has any value(1), check to true otherwise not check
			 $("#monday2").prop("checked",trip["monday"]?true:false);
			 $("#tuesday2").prop("checked",trip["tuesday"]?true:false);
			 $("#wednesday2").prop("checked",trip["wednesday"]?true:false);
			 $("#thursday2").prop("checked",trip["thursday"]?true:false);
			 $("#friday2").prop("checked",trip["friday"]?true:false);
			 $("#saturday2").prop("checked",trip["saturday"]?true:false);
			 $("#sunday2").prop("checked",trip["sunday"]?true:false);
			 
			 $('input[name="time2"]').val(trip["time"]);
			 $(".one-off2").hide(); $(".regular2").show();
		   }else{
			    $('#no2').prop('checked', true);
			   
			    $('input[name="date2"]').val(trip["date"]);
			    $('input[name="time2"]').val(trip["time"]);
			   
			    $(".regular2").hide(); $(".one-off2").show();
		   }
	}
	
function getEditTripDepartureCoordinates(){
		geocoder.geocode(
			{
			'address' : document.getElementById("departure2").value
		},
			function(results, status){
				if(status == google.maps.GeocoderStatus.OK){
				     departureLongitude = results[0].geometry.location.lng();
				     departureLatitude = results[0].geometry.location.lat();
					 data.push(
						 {
							 name: 'departureLongitude', value: departureLongitude
						 });
						 
					 data.push(
						 {
							 name: 'departureLatitude', value: departureLatitude
						 });
						 
						getEditTripDestinationCoordinates();
				   }else{
				   		getEditTripDestinationCoordinates();
				   }
			}
	);
}	

function getEditTripDestinationCoordinates(){
		geocoder.geocode(
			{
			'address' : document.getElementById("destination2").value
		},
			function(results, status){
				if(status == google.maps.GeocoderStatus.OK){
				     destinationLongitude = results[0].geometry.location.lng();
				     destinationLatitude = results[0].geometry.location.lat();
					 data.push(
						 {
							 name: 'destinationLongitude', value: departureLongitude
						 });
						 
					 data.push(
						 {
							 name: 'destinationLatitude', value: destinationLatitude
						 });
						 submitEditTripRequest();
				   }else{
				   		submitEditTripRequest();
				   }
			}
	);
	}

function submitEditTripRequest(){
		
		  // send AJAX call to updatetrips.php file
        $.ajax({
            url: "updatetrips.php",
			data: data, // our variable data
            type: "POST",
             // AJAX Call successful: show error or success message
            success: function(returnedData){
				// hide spinner
					$("#spinner").hide();
                if(returnedData){
					 // errors message
					 $("#editTripMessage").html(returnedData);
					
					// show error message with animation
					$("#editTripMessage").slideDown();
				}else{
					// delete previous error message:
					$("#editTripMessage").attr("class",'alert');
					$("#editTripMessage").html("");
					
					// hide modal
					$("#editTripModal").modal('hide');
					
					// reset form
					$('#editTripForm')[0].reset();
					
					// reset google display
					directionsDisplay.setMap(null);
				
					// load trips
					getTrips();
				}
            },
            error: function(){
				// hide spinner
					$("#spinner").hide();
                 // AJAX Call fails: show Ajax Call error
                $("#editTripMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
				
				// show error message with animation
				$("#editTripMessage").slideDown();
            }
        });
	}	
	
// Click on Edit Button inside a trip
	$("#editTripModal").on('show.bs.modal', function(event){
		$("#editTripMessage").empty(); // empty any left error message
		
		// button which opened the modal
		var $invoker = $(event.relatedTarget); 
		
		
		// ajax call to get details of the trip
		    $.ajax({
            url: "getTripDetails.php",
			method: "POST",
			data: {
				trip_id: $invoker.data('trip_id') // we called trip_id, that data will send using POST method  and this invoker variable button has field called data, which can access this piece  data-trip_id='$trip_id'
			},	
             // AJAX Call successful: show error or success message
            success: function(returnedData){
				if(returnedData){
					if(returnedData == "error"){
						 $("#editTripMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
						
					}else{
						trip = JSON.parse(returnedData); // we get json file, and then we can parse json to array then we can easly access and manipulate in here 
				
						// fill edit trip form using the JSON parsed data
						formatModal();
					}
				}
            },
            error: function(){
                 // AJAX Call fails: show Ajax Call error
                $("#editTripMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
            }
        });
		
		// submit edit modal
		$('#editTripForm').submit(function(event){
			// show spinner
		  $("#spinner").show();
		
		// hide error,success message
		$("#editTripMessage").hide();
			
			
		event.preventDefault();
		
		data = $(this).serializeArray();
			
		data.push({name: 'trip_id', value: $invoker.data('trip_id')});
		getEditTripDepartureCoordinates();
		});
		
		// delete trip in modal
		$("#deleteTrip").click(function(){
			// show spinner
			$("#spinner").show();
			// hide any errror messages
			$("#editTripMessage").hide();
			
			// ajax call to deletetrip.php file
		    $.ajax({
            url: "deletetrips.php",
			method: "POST",
			data: {
				trip_id: $invoker.data('trip_id') // we called trip_id, that data will send using POST method  and this invoker variable button has field called data, which can access this piece  data-trip_id='$trip_id'
			},	
             // AJAX Call successful: show error or success message
            success: function(returnedData){
				// hide spinner
					$("#spinner").hide();
				if(returnedData){
					 $("#editTripMessage").html("<div class='alert alert-danger'>The trip could not be deleted. Please try again! </div>");
					
					 // show some animation
					 $("#editTripMessage").slideDown();
				}else{
					// successfully deleted, now close and show all trips 
					$("#editTripModal").modal('hide');
					getTrips();
				}
            },
            error: function(){
				// hide spinner
					$("#spinner").hide();
                 // AJAX Call fails: show Ajax Call error
                $("#editTripMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
				// show some animation
					$("#editTripMessage").slideDown();
            }
		});
	   });
		
	}); // edit trip modal show end 
});

