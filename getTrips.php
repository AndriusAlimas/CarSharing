<?php
	session_start();
	include("connection.php");

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM `carsharetrips` WHERE `user_id`= '$user_id'";

$results = mysqli_query($connect,$sql);

if($results){
	if(mysqli_num_rows($results) > 0){
		while($row = mysqli_fetch_array($results,MYSQLI_ASSOC)){
			// check frequency
			if($row['regular'] == "N"){
				$frequency = "One-off journey.";
				$time = $row['date'] . " at " . $row['time'] . ".";
			}else{
				$frequency = "Regular.";
// old method:		
//					$arrayDays = [];
//
//					$row["monday"] == 1 ? array_push($arrayDays, "Mon") : "";
//					$row["tuesday"] == 1 ? array_push($arrayDays, "Tue") : "";
//					$row["wednesday"] == 1 ? array_push($arrayDays, "Wed") : "";
//					$row["thursday"] == 1 ? array_push($arrayDays, "Thu") : "";
//					$row["friday"] == 1 ? array_push($arrayDays, "Fri") : "";
//					$row["saturday"] == 1 ? array_push($arrayDays, "Sat") : "";
//					$row["sunday"] == 1 ? array_push($arrayDays, "Sun") : "";

					$arrayDays = [];
					$weekdays = ['monday'=>'Mon','tuesday'=>'Tue', 'wednesday'=>'Wed', 'thursday'=>'Thu', 'friday'=>'Fri', 'saturday'=>'Sat', 'sunday'=>'Sun'];
					
					foreach($weekdays as $key => $value){
						$row[$key] == 1 ? array_push($arrayDays, $value) : "";
					}
				// seperate array elements with -
				 $time = implode("-", $arrayDays)." at " .$row['time'].".";
			}
			
			$departure = $row['departure'];
			$destination = $row['destination'];
			$price = $row['price'];
			$seatsAvailable = $row['seatsavailable'];
			$trip_id = $row['trip_id'];
			
			echo "
			 <div class='row trip'>
				<div class='col-sm-8 journey'>
					<div><span class='departure'>Departure:</span> $departure.</div>
					<div><span class='destination'>Destination:</span> $destination.</div>
					<div class='time'>$time </div>
					<div>$frequency</div>
				</div>
				<div class='col-sm-2'> 
					<div class='price'>£$price</div>
					<div class='perSeat'>Per Seat </div>
					<div class='seatsAvailable'>$seatsAvailable left</div>
				</div>
				<div class='col-sm-2'> 
					<button class='btn btn-lg green' data-toggle='modal' data-target='#editTripModal' data-trip_id='$trip_id'>Edit</button>
				</div>
			 </div>
			";
		}
	}else{
		echo "<div class='alert alert-warning'>You have not created any trips yet!</div>";
	}
}
?>