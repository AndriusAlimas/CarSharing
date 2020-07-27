<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("location: index.php");
}else{
    include ('connection.php');

    $user_id = $_SESSION['user_id'];

    // get username
    $sql = "SELECT * FROM `users` WHERE `user_id`='$user_id'";

    $result = mysqli_query($connect,$sql);

    $count = mysqli_num_rows($result);

    if($count == 1){
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $username = $row['username'];
		$picture = $row['profilepicture'];
    }else{
        echo "There was an error retrieving the username from the database";
    }

    if(isset($connect)){
        mysqli_close($connect);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <title>My Trips</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styling.css" rel="stylesheet">  
      
    <!--Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Arvo&display=swap" rel="stylesheet">
      
	<!--	  Google Maps script -->
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcbYkIHdRcGU0rE_2C5TfjT-Qg0tni-c8&libraries=places"></script>
	  
	 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-3.5.1.js"></script>
    <script src="js/bootstrap.min.js"></script> 
	  
	<!--	jQuery UI  -->
	  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/sunny/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <style>
        #container{
            margin-top: 120px;
        }  
        
        .buttons{
            margin-bottom: 20px;
        }

    </style>  
  </head>
  <body>
	  
    <!-- Navigation Bar -->  
      <nav role="navigation" class="navbar navbar-custom navbar-fixed-top">
		  
        <div class="container-fluid">
			
            <div class="navbar-header">
				
                <a class="navbar-brand">Car Sharing</a>
                <button type="button" class="navbar-toggle" data-target="#navbarCollapse" data-toggle="collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>  
			
            <div id="navbarCollapse" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
						<li><a href="index.php">Search</a></li>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="#">Help</a></li>
                        <li><a href="#">Contact us</a></li>
                        <li class="active" ><a  href="#">My Trips</a></li>
                    </ul>
      <ul class="nav navbar-nav navbar-right">
						<li><a href="#"><div data-toggle="modal" data-target="#updatePicture">
							<?php
								if(empty($picture)){
									echo "<img class='preview' src='images/noimage.jpg'/>";
								}else{
									echo "<img class='preview' src='$picture'/>";
								}
							?>
						</div></a></li>
                        <li><a href="#"><?php echo  $username; ?></a></li>
                        <li><a href="index.php?logout=1">Log out</a></li>
                    </ul>
            </div>
        </div>
      </nav>
      
    <!-- Main Container -->  
        <div id="container" class="container">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
					<div>
						<button type="button" class="btn btn-lg green" data-toggle="modal" data-target="#addTripModal">
							Add trips
						</button>
					
					</div>
					
					<div id="myTrips" class="trips">
						 <!--Ajax Call to PHP file --> 
					</div>
					
				</div>
            </div>
        </div>
      
	   <!--Add Trip Form Modal --> 
      <form  method="post" id="addTripForm">
            <div class="modal" id="addTripModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                    <div class="modal-content">
						
							<!--modal header-->
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4>New trip:</h4>
                        </div>    
						
							<!--modal body-->
                        <div class="modal-body">
							
                         <!-- Add Trip message from PHP file-->    
                           <div id="addTripMessage">
                            
                           </div>
							
							<!-- Google Map -->    
                           <div id="googleMap">
                            
                           </div>
							
                             <!-- Departure input -->
                           <div class="form-group">
                               <label for="departure" class="sr-only">Departure:</label>
                                <input class="form-control" type="text" name="departure" id="departure" placeholder="Departure">
                            </div>   
							
							 <!-- Destination input -->
                           <div class="form-group">
                               <label for="destination" class="sr-only">Destination:</label>
                                <input class="form-control" type="text" name="destination" id="destination" placeholder="Destination">
                            </div>   
							
							 <!-- Price input -->
                           <div class="form-group">
                               <label for="price" class="sr-only">Price:</label>
                                <input class="form-control" type="number" name="price" id="price" placeholder="Price">
                            </div>  
							
							 <!-- Seats input -->
                           <div class="form-group">
                               <label for="seatsAvailable" class="sr-only">Seats available:</label>
                                <input class="form-control" type="number" name="seatsAvailable" id="seatsAvailable" placeholder="Seats available">
                            </div> 
							
							<!-- Radio button for regular -->
                           <div class="form-group">
								 <!-- for yes-->
								<div class="radio-inline">
									<input class="form-check-input" type="radio" name="regular" id="yes" value="Y"/>
									<label class="form-check-label" for="yes">Regular</label>
								</div>
								  <!--for no-->
							   	<div class="radio-inline">
									<input class="form-check-input" type="radio" name="regular" id="no" value="N"/>
									<label class="form-check-label" for="no">One-off</label>
								</div>
							</div>
							
						   <!-- CheckBox buttons for all week days -->
                           <div class="form-group regular">
								<div class="checkbox checkbox-inline">
									<label><input class="form-check-input" type="checkbox" name="monday" id="monday" value="1"/>Monday</label>
									<label><input class="form-check-input" type="checkbox" name="tuesday" id="tuesday" value="1"/>Tuesday</label>
									<label><input class="form-check-input" type="checkbox" name="wednesday" id="wednesday" value="1"/>Wednesday</label>
									<label><input class="form-check-input" type="checkbox" name="thursday" id="thursday" value="1"/>Thursday</label>
									<label><input class="form-check-input" type="checkbox" name="friday" id="friday" value="1"/>Friday</label>
									<label><input class="form-check-input" type="checkbox" name="saturday" id="saturday" value="1"/>Saturday</label>
									<label><input class="form-check-input" type="checkbox" name="sunday" id="sunday" value="1"/>Sunday</label>
								</div>
						   </div>
							
							 <!-- Date input -->
                           <div class="form-group one-off">
                               <label for="date" class="sr-only">Date:</label>
                                <input class="form-control" readonly="readonly"name="date" id="date">
                            </div> 
							
							 <!-- Time input -->
                           <div class="form-group regular one-off">
                               <label for="time" class="sr-only">Time:</label>
                                <input class="form-control" type="time" name="time" id="time">
                            </div> 
							
                        </div>    
                        <div class="modal-footer">
                            <input class="btn btn-primary" name="createTrip" type="submit" value="Create Trip">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>    
                  </div>
              </div>
          </div>
      </form>
	  
	    <!--Edit Trip Form Modal --> 
      <form  method="post" id="editTripForm">
            <div class="modal" id="editTripModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                    <div class="modal-content">
						
							<!--modal header-->
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4>Edit trip:</h4>
                        </div>    
						
							<!--modal body-->
                        <div class="modal-body">
							
                         <!-- Edit Trip message from PHP file-->    
                           <div id="editTripMessage">
                            
                           </div>
											
                             <!-- Departure input -->
                           <div class="form-group">
                               <label for="departure2" class="sr-only">Departure:</label>
                                <input class="form-control" type="text" name="departure2" id="departure2" placeholder="Departure">
                            </div>   
							
							 <!-- Destination input -->
                           <div class="form-group">
                               <label for="destination2" class="sr-only">Destination:</label>
                                <input class="form-control" type="text" name="destination2" id="destination2" placeholder="Destination">
                            </div>   
							
							 <!-- Price input -->
                           <div class="form-group">
                               <label for="price2" class="sr-only">Price:</label>
                                <input class="form-control" type="number" name="price2" id="price2" placeholder="Price">
                            </div>  
							
							 <!-- Seats input -->
                           <div class="form-group">
                               <label for="seatsAvailable2" class="sr-only">Seats available:</label>
                                <input class="form-control" type="number" name="seatsAvailable2" id="seatsAvailable2" placeholder="Seats available">
                            </div> 
							
							<!-- Radio button for regular -->
                           <div class="form-group">
								 <!-- for yes-->
								<div class="radio-inline">
									<input class="form-check-input" type="radio" name="regular2" id="yes2" value="Y"/>
									<label class="form-check-label" for="yes2">Regular</label>
								</div>
								  <!--for no-->
							   	<div class="radio-inline">
									<input class="form-check-input" type="radio" name="regular2" id="no2" value="N"/>
									<label class="form-check-label" for="no2">One-off</label>
								</div>
							</div>
							
						   <!-- CheckBox buttons for all week days -->
                           <div class="form-group regular2">
								<div class="checkbox checkbox-inline">
									<label><input class="form-check-input" type="checkbox" name="monday2" id="monday2" value="1"/>Monday</label>
									<label><input class="form-check-input" type="checkbox" name="tuesday2" id="tuesday2" value="1"/>Tuesday</label>
									<label><input class="form-check-input" type="checkbox" name="wednesday2" id="wednesday2" value="1"/>Wednesday</label>
									<label><input class="form-check-input" type="checkbox" name="thursday2" id="thursday2" value="1"/>Thursday</label>
									<label><input class="form-check-input" type="checkbox" name="friday2" id="friday2" value="1"/>Friday</label>
									<label><input class="form-check-input" type="checkbox" name="saturday2" id="saturday2" value="1"/>Saturday</label>
									<label><input class="form-check-input" type="checkbox" name="sunday2" id="sunday2" value="1"/>Sunday</label>
								</div>
						   </div>
							
							 <!-- Date input -->
                           <div class="form-group one-off2">
                               <label for="date2" class="sr-only">Date:</label>
                                <input class="form-control" readonly="readonly" name="date2" id="date2">
                            </div> 
							
							 <!-- Time input -->
                           <div class="form-group regular2 one-off2">
                               <label for="time2" class="sr-only">Time:</label>
                                <input class="form-control" type="time" name="time2" id="time2">
                            </div> 
							
                        </div>    
                        <div class="modal-footer">
                            <input class="btn btn-primary" name="updateTrip" type="submit" value="Edit Trip">
							<input class="btn btn-danger" name="deleteTrip" value="Delete" id="deleteTrip" type="button">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>    
                  </div>
              </div>
          </div>
      </form>
	  
    <!-- Footer -->  
      <div class="footer">
          <div class="container">
            <p>Developed by &copy; Andrius Alimas 2020 April - <?php $today = date("Y M"); echo $today;?>.</p>
          </div>
      </div>

	   <!-- Spinner -->  
	  <div id="spinner">
		  	<img src="images/ajax-loader.gif" width="64" height="64">
	  </div>
	  
	 <!--Map script -->
	<script src="js/map.js"></script>  
	 <!--My trip script -->
    <script src="js/mytrips.js"></script>
  </body>
</html>