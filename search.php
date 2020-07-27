<?php
	session_start();

	include("connection.php");


	// define error messages
	$missingDeparture = '<p><strong>Please enter your departure!</strong></p>';
 	$invalidDeparture = '<p><strong>Please enter a valid departure!</strong></p>';
 	$missingDestination = '<p><strong>Please enter your destination!</strong></p>';
 	$invalidDestination = '<p><strong>Please enter a valid destination!</strong></p>';
	
	// get all inputs:
	$departure = $_POST["departure"];
	$destination = $_POST["destination"];

	// all errors store in this variable 
	$errors = "";

	// contains filterIt() function that helps sanitise and validate our variables
	include('filter.php');

	// check departure
	 if(empty($departure)){
		$errors .= $missingDeparture;
	}else{
		// check coordinates
		if(!isset($_POST["departureLatitude"]) or !isset($_POST["departureLongitude"]) ){
			$errors .= $invalidDeparture;
		}else{
			$departureLatitude =  $_POST["departureLatitude"];
			$departureLongitude = $_POST["departureLongitude"];
			$departure = filterIt($departure,$connect,true);
		}
	}

	// check destination
	 if(empty($destination)){
		$errors .= $missingDestination;
	}else{
		// check coordinates
		if(!isset($_POST["destinationLatitude"]) or !isset($_POST["destinationLongitude"]) ){
			$errors .= $invalidDestination;
		}else{
			$destinationLatitude =  $_POST["destinationLatitude"];
			$destinationLongitude = $_POST["destinationLongitude"];
			$destination = filterIt($destination,$connect,true);
		}
	}

	// if there is an error and print an error message 
	if($errors){
		$resultMessage = "<div class='alert alert-danger'>$errors</div>";
		exit($resultMessage);
	}

	// No ERRORS FROM HERE

	// set search radius
	$searchRadius = 10;
	
	// Longtitude out of range
	$departureLngOutOfRange = false;
	$destinationLngOutOfRange = false;

// DEPARTURE min max  *Longtitude*
	$deltaLongitudeDeparture = $searchRadius * 360 / (24901 * cos(deg2rad($departureLatitude))); 

	// MIN
	$minLongitudeDeparture = $departureLongitude - $deltaLongitudeDeparture;

	if($minLongitudeDeparture < -180){
		$departureLngOutOfRange = true;
		$minLongitudeDeparture += 360;
	}

	// MAX
	$maxLongitudeDeparture = $departureLongitude + $deltaLongitudeDeparture;

	if($maxLongitudeDeparture > 180){
		$departureLngOutOfRange = true;
		$maxLongitudeDeparture -= 360;
	}
	
	// DEPARTURE min max  *Latitude*
	$deltaLatitudeDeparture = $searchRadius * 180 / 12430;

	// MIN
	$minLatitudeDeparture = $departureLatitude - $deltaLatitudeDeparture;

	if($minLatitudeDeparture < -90){
		$minLatitudeDeparture = -90;
	}

	// MAX
	$maxLatitudeDeparture = $departureLatitude + $deltaLatitudeDeparture;
	if($maxLatitudeDeparture > 90){
		$maxLatitudeDeparture = 90;
	}

// DESTINATION min max *Longtitude*
	$deltaLongitudeDestination = $searchRadius * 360 / (24901 * cos(deg2rad($destinationLatitude)));

	// MIN
	$minLongitudeDestination = $destinationLongitude - $deltaLongitudeDestination;

	if($minLongitudeDestination < -180){
		$destinationLngOutOfRange = true;
		$minLongitudeDestination += 360;
	}
	
	// MAX
	$maxLongitudeDestination = $destinationLongitude + $deltaLongitudeDestination;

	if($maxLongitudeDestination > 180){
		$destinationLngOutOfRange = true;
		$maxLongitudeDestination -= 360;
	}
	
	// DESTINATION min max  *Latitude*
	$deltaLatitudeDestination = $searchRadius * 180 / 12430;

	// MIN
	$minLatitudeDestination = $destinationLatitude - $deltaLatitudeDestination;

	if($minLatitudeDestination < -90){
		$minLatitudeDestination = -90;
	}

	// MAX
	$maxLatitudeDestination = $destinationLatitude + $deltaLatitudeDestination;
	if($maxLatitudeDestination > 90){
		$maxLatitudeDestination = 90;
	}


	// start query 
	$sql = "SELECT * FROM `carsharetrips` WHERE ";

	// FOR DEPARTURE:
	// departure Longtitude
	if($departureLngOutOfRange){
		$sql .= "((`departureLongitude` > '$minLongitudeDeparture') OR (`departureLongitude` > '$maxLongitudeDeparture'))";
	}else{
		$sql .= "(`departureLongitude` BETWEEN '$minLongitudeDeparture' AND '$maxLongitudeDeparture' )";
	}

	// departure Latitude
	$sql .= " AND (`departureLatitude` BETWEEN '$minLatitudeDeparture' AND '$maxLatitudeDeparture' )";

	// FOR DESTINATION:
	// destination Longitude
	if($destinationLngOutOfRange){
		$sql .= " AND ((`destinationLongitude` > '$minLongitudeDestination') OR (`destinationLongitude` > '$maxLongitudeDestination'))";
	}else{
		$sql .= " AND (`destinationLongitude` BETWEEN '$minLongitudeDestination' AND '$maxLongitudeDestination' )";
	}

	// destination Latitude
	$sql .= " AND (`destinationLatitude` BETWEEN '$minLatitudeDestination' AND '$maxLatitudeDestination' )";

	// RUN QUERY
	$result = mysqli_query($connect,$sql);

	if(!$result){
		exit("ERROR: Unable to execute: $sql." . mysqli_error($connect));
	}

	if(mysqli_num_rows($result) == 0){
	
		exit("<div class='alert alert-info noresults'>There are no journeys matching your search! 
		</div>");
	}

	echo "<div class='alert alert-info journeySummary'>From $departure to $destination.<br />Closest Journeys:</div>";

	echo "<div id='tripResults'>";
		
	// cycle through the trips
  while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		
		// get trip details 
		// check frequency
				if($row['regular'] == "N"){
					// one off journey
					$frequency = "One-off journey.";
					$time = $row['date'] . " at " . $row['time'] . ".";
					// check the date that is not already pass
						$source =  $row['date'];
						$tripDate = DateTime::createFromFormat('D d M, Y', $source);
						$today = date('D d M, Y');
						$todayDate = DateTime::createFromFormat('D d M, Y', $today);
					
					if($tripDate < $todayDate){
						continue;
					}
				}else{
					$frequency = "Regular.";
					
					$arrayDays = [];
					$weekdays = ['monday'=>'Mon','tuesday'=>'Tue', 'wednesday'=>'Wed', 'thursday'=>'Thu', 'friday'=>'Fri', 'saturday'=>'Sat', 'sunday'=>'Sun'];
					
					foreach($weekdays as $key => $value){
						$row[$key] == 1 ? array_push($arrayDays, $value) : "";
					}
					
					// seperate array elements with -
					 $time = implode("-", $arrayDays)." at " .$row['time'].".";
				}

				$tripDeparture = $row['departure'];
				$tripDestination = $row['destination'];
				$price = $row['price'];
				$seatsAvailable = $row['seatsavailable'];
	  
	  			// get user_id
	  			$person_id = $row['user_id'];
	  
	  			// run query to get user details
	  			$sql2 = "SELECT * FROM `users` WHERE `user_id` = '$person_id' LIMIT 1";
	  
	  			$result2 = mysqli_query($connect,$sql2);
	  
	  			if(!$result2){
					exit("ERROR: Unable to execute: $sql2." . mysqli_error($connect));
				}
	  
	  			// so lets get all user details from that row
	  			$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
	  
	  			$firstName = $row2['first_name'];	
	  			$gender = $row2['gender'];
	  			$moreInformation = $row2['moreinformation'];
	  			$picture = $row2['profilepicture'];
	  			
	  			if($_SESSION['user_id']){
					$phoneNumber = $row2['phonenumber'];
				}else{
					$phoneNumber = "Please sign up! Only members have access to contact information.";
				}
	  
	  			// print trip
	  			echo 
				"<h4 class='row'>
						<div class ='col-sm-2 journey'>
							<div class='driver'>$firstName</div>
							
							<div><img class='profilePic' src='$picture' /></div>
						</div>
						<div class ='col-sm-8 journey'>
							<div><span class='departure'>Departure:</span> $tripDeparture.</div>
							<div><span class='destination'>Destination:</span> $tripDestination.</div>
							<div class='time'>$time </div>
							<div>$frequency</div>
						</div>
						<div class ='col-sm-2 journey2'>
							<div class='price'>£$price</div>
							<div class='perSeat'>Per Seat </div>
							<div class='seatsAvailable'>$seatsAvailable left</div>
						</div>
					</h4>
					
					<div class='moreInfo'>
						<div>
							<div>Gender: $gender</div>
							<div>&#9742: $phoneNumber</div>
						</div>
						<div class='aboutMe'>About me: $moreInformation</div>
					</div>
				";
	 
  }
	echo "</div>";
?>