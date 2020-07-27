<?php
session_start();

// connect to DB
include("connection.php");

// logout
include("logout.php");

// remember me
include('remember.php');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <title>Car Sharing Website</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styling.css" rel="stylesheet">  
      
    <!--Google Font -->
   <link href="https://fonts.googleapis.com/css2?family=Chelsea+Market&display=swap" rel="stylesheet">
	  
<!--	  Google Maps script -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcbYkIHdRcGU0rE_2C5TfjT-Qg0tni-c8&libraries=places"></script>
	
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
 <script src="js/jquery-3.5.1.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="js/bootstrap.min.js"></script>	  
	  
<!--	jQuery UI  -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/sunny/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	  
  </head>
	
  <body>
    <!-- Navigation Bar -->  
	 <?php
	  	if(isset($_SESSION['user_id'])){
			include("navigationbarconnected.php");
		}else{
			include("navigationbarnotconnected.php");
		}
	  ?>
           
	<!--	  Main Container-->
	  <div class="container-fluid" id="myContainer">
		  
	  	<div class="row">
		  	<div class="col-md-6 col-md-offset-3">
				<h1>Plan your next trip now!</h1>
				<p>Save Money! Save the Environment!</p>
				<p>You can save up to 3000$ a year using car sharing!</p>
				
				<!--Search Form-->
				<form class="form-inline" id="searchForm">
					<div class="form-group">
					  <label class="sr-only" for="departure">Departure</label>	
						<input type="text" placeholder="Departure" name="departure" id="departure">
					</div>
					
					<div class="form-group">
					  <label class="sr-only" for="destination">Destination</label>	
						<input type="text" placeholder="Destination" name="destination" id="destination">
					</div>
					
					<input type="submit" value="Search" class="btn btn-lg green" name="search">
					
				</form> <!--Search Form End-->

				<!--Google Map-->
				<div id="googleMap"></div>
					
					<!--Sign up button-->
				<?php
					if(!isset($_SESSION['user_id'])){
							echo "<button class='btn btn-lg green signup' data-toggle='modal' data-target='#signupModal'>Sign up-It's free</button>";
						   }
			  	?>
				
				<!--search results showing error messages, or success result-->
				<div id="searchResults"></div>
			</div>
		  </div>
	  </div>
	  
    <!-- Login Form -->  
        <form  method="post" id="loginForm">
            <div class="modal" id="loginModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4>Login:</h4>
                        </div>    
                        <div class="modal-body">
                        <!-- Login message from PHP file-->    
                           <div id="loginMessage">
                            
                           </div>
                               <!-- Email input -->
                             <div class="form-group">
                               <label for="loginEmail" class="sr-only">Email:</label>
                                <input class="form-control" type="email" name="loginEmail" id="loginEmail" placeholder="Email" maxlength="50">
                             </div>     
                               <!-- Password inputs -->
                            <div class="form-group">
                               <label for="loginPassword" class="sr-only">Password:</label>
                                <input class="form-control" type="password" name="loginPassword" id="loginPassword" placeholder="Password" maxlength="30">
                            </div>
                            <div class="checkbox">
                                <label for="rememberMe">
                                    <input type="checkbox" name="rememberMe" id="rememberMe">
                                    Remember me
                                </label>
                             <a data-target="#forgotPasswordModal" data-toggle="modal" class="pull-right" 
                                style="cursor:pointer" data-dismiss="modal">
                             Forgot Password?
                            </a>
                         </div>
                     </div>    
                        <div class="modal-footer">
                            <input class="btn green" name="login" type="submit" value="Login">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-target="#signupModal" data-toggle="modal">Register</button>
                        </div>    
                  </div>
              </div>
          </div>
      </form>
      
    <!-- Sign Up Form Modal --> 
      <form  method="post" id="signupForm">
            <div class="modal" id="signupModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4>Sign up today and Start using our Car Sharing App!</h4>
                        </div>    
                        <div class="modal-body">
							
                         <!-- Sign up message from PHP file-->    
                           <div id="signupMessage">
                            
                           </div>
							
                             <!-- Username input -->
                           <div class="form-group">
                               <label for="username" class="sr-only">Username:</label>
                                <input class="form-control" type="text" name="username" id="username" placeholder="User Name" maxlength="30">
                            </div>   
							
							 <!-- First Name input -->
                           <div class="form-group">
                               <label for="firstName" class="sr-only">First Name:</label>
                                <input class="form-control" type="text" name="firstName" id="firstName" placeholder="First Name" maxlength="30">
                            </div>   
							
							 <!-- Last Name input -->
                           <div class="form-group">
                               <label for="lastName" class="sr-only">Last Name:</label>
                                <input class="form-control" type="text" name="lastName" id="lastName" placeholder="Last Name" maxlength="30">
                            </div>   
							
                               <!-- Email input -->
                             <div class="form-group">
                               <label for="signupEmail" class="sr-only">Email:</label>
                                <input class="form-control" type="email" name="signupEmail" id="signupEmail" placeholder="Email Address" maxlength="50">
                             </div>     
							
                               <!-- Password inputs -->
                            <div class="form-group">
                               <label for="signupPassword" class="sr-only">Choose a password:</label>
                                <input class="form-control" type="password" name="signupPassword" id="signupPassword" placeholder="Choose a password" maxlength="30">
                            </div>   
							
                            <div class="form-group">
                               <label for="signupPasswordR" class="sr-only">Confirm password:</label>
                                <input class="form-control" type="password" name="signupPasswordR" id="signupPasswordR" placeholder="Confirm a password" maxlength="30">
                            </div>
							
							 <!--Telephone number input-->
							<div class="form-group">
                               <label for="phoneNumber" class="sr-only">Telephone Number:</label>
                                <input class="form-control" type="text" name="phoneNumber" id="phoneNumber" placeholder="Telephone Number" maxlength="20">
                            </div>
							
							 <!--Gender radio inputs-->
							<div class="form-group">
                               <label><input type="radio" name="gender" id="male" value="male">&nbsp;&nbsp;Male</label>
								<label><input type="radio" name="gender" id="female" value="female">&nbsp;&nbsp;Female</label>
                            </div>
							
							<!--Comments Field-->
							<div class="form-group">
								<label for="moreInformation">Comments:</label>
								<textarea id="moreInformation" name="moreInformation" class="form-control" rows="4" maxlength="300"></textarea>
							</div>
							
                        </div>    
                        <div class="modal-footer">
                            <input class="btn green" name="signup" type="submit" value="Sign up">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>    
                  </div>
              </div>
          </div>
      </form>
      
    <!-- Forgot Password Form -->  
       <form  method="post" id="forgotPasswordForm">
            <div class="modal" id="forgotPasswordModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4>Forgot Password?Enter your email address:</h4>
                        </div>    
                        <div class="modal-body">
                        <!-- Forgot Password message from PHP file-->    
                           <div id="forgotPasswordMessage">
                            
                           </div>
                               <!-- Email input -->
                             <div class="form-group">
                               <label for="forgotEmail" class="sr-only">Email:</label>
                                <input class="form-control" type="email" name="forgotEmail" id="forgotEmail" placeholder="Email" maxlength="50">
                             </div>     
                        </div>    
                        <div class="modal-footer">
                            <input class="btn green" name="forgotPassword" type="submit" value="Submit">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-target="#signupModal" data-toggle="modal">Register</button>
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
	  
    <script src="js/index.js"></script>
	<!--Map script -->
	<script src="js/map.js"></script>  
  </body>
</html>
