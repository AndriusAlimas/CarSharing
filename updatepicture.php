<?php
session_start();

$user_id = $_SESSION["user_id"];

// connect to DB
include("connection.php");

// get file name
$name = $_FILES["picture"]["name"];;

// get file extension
$extension = pathinfo($name,PATHINFO_EXTENSION);

// other file errors
$fileError = $_FILES["picture"]["error"];

// get temporaly location from uploaded file
$tmp_location = $_FILES["picture"]["tmp_name"];

// get permenent direction where we want move our uploaded file to our server
$permanetDestination = 'profilePictures/'. md5(time()) . ".$extension";

// move this uploaded file to our server
if(move_uploaded_file($tmp_location, $permanetDestination)){
	$sql = "UPDATE `users` SET `profilepicture` = '$permanetDestination' WHERE `user_id` = '$user_id'";
	
	$result = mysqli_query($connect, $sql);
	
	if(!$result){
		echo "<div class='alert alert-danger'>Unable to update profile picture! Please try again later.</div>";
	}
}else{
	echo "<div class='alert alert-danger'>Unable to update profile picture! Please try again later.</div>";
}

if($fileError > 0){
	echo "<div class='alert alert-danger'>File error! Error Code: $fileError </div>";
}

?>