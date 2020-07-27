<?php
session_start();

 include ('connection.php');

// get trip id sended from ajax call using POST method
$trip_id = $_POST['trip_id'];

$sql = "SELECT * FROM `carsharetrips` WHERE `trip_id`='$trip_id'";

$result = mysqli_query($connect, $sql);

if(!$result){
	echo "error";
	
}else{
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	echo json_encode($row); // we need this to transform into json, that we can easly store in array and then with js we can easly manipulate array data
	
	
}
?>