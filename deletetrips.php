<?php
	include("connection.php");

	// get trip id
	$trip_id = $_POST["trip_id"];

	$sql = "DELETE FROM `carsharetrips` WHERE `trip_id` = '$trip_id'";

	$result = mysqli_query($connect,$sql);
	
	if(!$result){
		echo "error";
	}
?>