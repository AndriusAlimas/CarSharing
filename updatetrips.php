<?php
	session_start();
	
	$update = true;

	include("connection.php");

	// get all possible errors message and input fields variables
	include("errorsInputs.php");
	
	// get extra field that we need it
	$trip_id = $_POST["trip_id"];

	// sort all checks 
	include("checker.php");

// if there is an error and print an error message 
	if($errors){
		$resultMessage = "<div class='alert alert-danger'>$errors</div>";
		echo $resultMessage;
	}else{
		// no errors, prepare variables to the query
		$departure = filterIt($departure,$connect,true);
		$destination = filterIt($destination,$connect,true);
		
		$tableName = 'carsharetrips';
		
		$user_id = $_SESSION['user_id'];
		
		if($regular == "Y"){
			// query for a regular trip	
			$sql = "UPDATE $tableName SET `departure` = '$departure' , `departureLongitude` = '$departureLongitude', `departureLatitude` = '$departureLatitude', `destination` = '$destination', `destinationLongitude` = '$destinationLongitude', `destinationLatitude` = '$destinationLatitude', `price` = '$price', `seatsavailable` =  '$seatsAvailable', `regular` = '$regular', `monday` = '$monday', `tuesday` = '$tuesday', `wednesday` = '$wednesday', `thursday` = '$thursday', `friday` = '$friday', `saturday` = '$saturday', `sunday` = '$sunday', `time` = '$time' WHERE `trip_id` = '$trip_id' LIMIT 1";
		}else{
			// query for a one-off trip
			$sql = "UPDATE  $tableName SET `departure` = '$departure', `departureLongitude` = '$departureLongitude', `departureLatitude` = '$departureLatitude',  `destination` = '$destination', `destinationLongitude` = '$destinationLongitude', `destinationLatitude` = '$destinationLatitude', `price` = '$price', `seatsavailable` = '$seatsAvailable', `regular` = '$regular', `date` = '$date', `time` = '$time' WHERE `trip_id` = '$trip_id' LIMIT 1";
		}
		
		$results = mysqli_query($connect,$sql);
			
		// check if the query is successful
		if(!$results){
			echo "<div class='alert alert-danger'>There was an error! The trip could not be updated to the database!</div>";
		}
			
	}
?>
