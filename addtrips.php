<?php
	session_start();
	
	$update = false;

	include("connection.php");

	// get all possible errors message and input fields variables
	include("errorsInputs.php");

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
			$sql = "INSERT INTO $tableName (`user_id`, `departure`, `departureLongitude`, `departureLatitude`, `destination`, `destinationLongitude`, `destinationLatitude`, `price`, `seatsavailable`, `regular`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `time`) 
			VALUES ('$user_id', '$departure', '$departureLongitude', '$departureLatitude', '$destination', '$destinationLongitude', '$destinationLatitude', '$price', '$seatsAvailable', '$regular', '$monday', '$tuesday', '$wednesday', '$thursday', '$friday', '$saturday', '$sunday', '$time')";
		}else{
			// query for a one-off trip	
			$sql = "INSERT INTO $tableName (`user_id`, `departure`, `departureLongitude`, `departureLatitude`, `destination`, `destinationLongitude`, `destinationLatitude`, `price`, `seatsavailable`, `regular`, `date`, `time`) 
			VALUES ('$user_id', '$departure', '$departureLongitude', '$departureLatitude', '$destination', '$destinationLongitude', '$destinationLatitude', '$price', '$seatsAvailable', '$regular', '$date',  '$time')";	
		}
		
		$results = mysqli_query($connect,$sql);
			
		// check if the query is successful
		if(!$results){
			echo "<div class='alert alert-danger'>There was an error! The trip could not be added to the database!</div>";
		}
			
	}
?>