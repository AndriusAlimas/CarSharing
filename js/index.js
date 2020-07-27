// Ajax Call for the sign up form 
    // Once the form is submitted
    $("#signupForm").submit(function(event){
	// show spinner
	$("#spinner").show();
	
	// hide error message
	$("#signupMessage").hide();
		
         // prevent default php processing
        event.preventDefault();

        // collect user input
        var datapost = $(this).serializeArray();

        // send them to signup.php using AJAX
        $.ajax({
            url: "signup.php",
            type: "POST",
            data: datapost,

             // AJAX Call successful: show error or success message
            success: function(data){
               if(data){
				   // hide spinner
					$("#spinner").hide();
				   
                    $("#signupMessage").html(data);
				   
				   // show message with nice animation 
				   $("#signupMessage").slideDown();
               }
            },
            error: function(){
				 // hide spinner
					$("#spinner").hide();
				
                 // AJAX Call fails: show Ajax Call error
                $("#signupMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
				
				 // show message with nice animation 
				  $("#signupMessage").slideDown();
            }
        });
    });

// Ajax Call for the login form
    // Once the form is submitted
    $("#loginForm").submit(function(event){
	// show spinner
	$("#spinner").show();
	
	// hide error,success message
	$("#loginMessage").hide();
		
         // prevent default php processing
        event.preventDefault();
        // collect user input
        var datapost = $(this).serializeArray();
        
        // send them to login.php using AJAX
        $.ajax({
            url: "login.php",
            type: "POST",
            data: datapost,
             // AJAX Call successful
            // if php files returns "success": redirect the user to notes page
            // otherwise show error message
            success: function(data){
               if(data.trim() == "success"){
                    window.location = "mainpageloggedin.php"
               }else{
				    // hide spinner
					$("#spinner").hide();
				   
                   $('#loginMessage').html(data);
				   
				   // show message with nice animation 
				  $("#loginMessage").slideDown();
               }
            },
            // AJAX Call fails: show Ajax Call error
            error: function(){
				// hide spinner
					$("#spinner").hide();
				
                $("#loginMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
				
				 // show message with nice animation 
				  $("#loginMessage").slideDown();
            }
        });
    });

// Ajax Call for the forgot password form
// Once the form is submitted
$("#forgotPasswordForm").submit(function(event){
	// show spinner
	$("#spinner").show();
	
	// hide error,success message
	$("#forgotPasswordMessage").hide();
	
    // prevent default php processing
    event.preventDefault();

    // collect user input
    var datapost = $(this).serializeArray();

    // send them to forgot-password.php using AJAX
    $.ajax({
        url: "forgot-password.php",
        type: "POST",
        data: datapost,
        success: function(data){
			 // hide spinner
				$("#spinner").hide();
			
            // AJAX Call successful: show error or success message
                $('#forgotPasswordMessage').html(data);
			
				// show message with nice animation 
				  $("#forgotPasswordMessage").slideDown();
                },
        // AJAX Call fails: show Ajax Call error
        error: function(){
			 // hide spinner
				$("#spinner").hide();
			
            $("#forgotPasswordMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
			
			// show message with nice animation 
			$("#forgotPasswordMessage").slideDown();
        }
    });
});

// create a geocoder object to use geocode
	var geocoder = new google.maps.Geocoder();
	var data;

// submit the search form
$("#searchForm").submit(function(event){
	// show spinner
	$("#spinner").show();
	
	// fade out trips
	$("#searchResults").fadeOut();
	
	event.preventDefault();
	
    data = $(this).serializeArray(); // collect all form inputs
	
	getSearchDepartureCoordinates();
});

// define functions
function getSearchDepartureCoordinates(){
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
						 
						getSearchDestinationCoordinates();
				   }else{
				   		getSearchDestinationCoordinates();
				   }
			}
	);
}

function getSearchDestinationCoordinates(){
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
						 submitSearchRequest();
				   }else{
				   		submitSearchRequest();
				   }
			}
	);
}

function submitSearchRequest(){
		  // send AJAX call to addtrips.php file
        $.ajax({
            url: "search.php",
			data: data, // our variable data
            type: "POST",
             // AJAX Call successful: show error or success message
            success: function(data2){
				// hide spinner
				$("#spinner").hide();
				
				$("#searchResults").html(data2);
				$("#tripResults").accordion({
					active: false,
					collapsible: true,
					heightStyle: "content",
					icons: false
				});
				
				
				// show results
				$("#searchResults").fadeIn();
            },
            error: function(){
				// hide spinner
				$("#spinner").hide();
				
                 // AJAX Call fails: show Ajax Call error
                $("#searchResults").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
				
				// show results
				$("#searchResults").fadeIn();
            }
        });
}

	