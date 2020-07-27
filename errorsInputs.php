<?php
// define error messages
	$missingDeparture = '<p><strong>Please enter your departure!</strong></p>';
 	$invalidDeparture = '<p><strong>Please enter a valid departure!</strong></p>';
 	$missingDestination = '<p><strong>Please enter your destination!</strong></p>';
 	$invalidDestination = '<p><strong>Please enter a valid destination!</strong></p>';
 	$missingPrice = '<p><strong>Plesase choose a price per seat!</strong></p>';
 	$invalidPrice = '<p><strong>Please choose a valid price per seat using numbers only!!</strong></p>';
 	$missingSeatsAvailable = '<p><strong>Please select the number of available seats!</strong></p>';
 	$invalidSeatsAvailable = '<p><strong>The number of available seats should contain digits only!</strong></p>';
 	$missingFrequency = '<p><strong>Please select a frequency!</strong></p>';
 	$missingDays = '<p><strong>Please select at least one weekday!</strong></p>';
 	$missingDate = '<p><strong>Please choose a date for your trip!</strong></p>';
 	$missingTime = '<p><strong>Please choose a time for your trip!</strong></p>';

	$num ="";
// get all inputs:
 if($update == true){
	 $num = "2";
 }
	
	$departure = $_POST["departure$num"];
	$destination = $_POST["destination$num"];
	$price = $_POST["price$num"];
	$seatsAvailable = $_POST["seatsAvailable$num"];
	$regular = $_POST["regular$num"];
	$date = $_POST["date$num"];
	$time = $_POST["time$num"];
	$monday = $_POST["monday$num"];
	$tuesday = $_POST["tuesday$num"];
	$wednesday = $_POST["wednesday$num"];
	$thursday = $_POST["thursday$num"];
	$friday = $_POST["friday$num"];
	$saturday = $_POST["saturday$num"];
	$sunday = $_POST["sunday$num"];
	
	// all errors store in this variable 
	$errors = "";
?>