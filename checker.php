<?php

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

// check price
	if(empty($price)){
		$errors .= $missingPrice;
	}elseif(preg_match('/\D/',$price)){
		$errors .= $invalidPrice;
	}else{
		$price = filterIt($price,$connect,true);
	}

// check seats available
	if(empty($seatsAvailable)){
		$errors .= $missingSeatsAvailable;
	}elseif(preg_match('/\D/',$seatsAvailable)){
		$errors .= $invalidSeatsAvailable;
	}else{
		$seatsAvailable = filterIt($seatsAvailable,$connect,true);
	}

	// check frequancy
	if(empty($regular)){
		$errors .= $missingFrequency;
	}elseif($regular == "Y"){ // it's regular
		if(empty($monday) && empty($tuesday) && empty($wednesday) && empty($thursday) && empty($friday) && empty($saturday) && empty($sunday)){
			$errors .= $missingDays;
		}
		if(empty($time)){
			$errors .= $missingTime;
		}
	}else{ // no regular
		if(empty($date)){
			$errors .= $missingDate;
		}
		if(empty($time)){
			$errors .= $missingTime;
		}
	}

?>