<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("location: index.php");
}else{
    include ('connection.php');

    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM `users` WHERE `user_id`='$user_id'";

    $result = mysqli_query($connect,$sql);

    $count = mysqli_num_rows($result);

    if($count == 1){
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $username = $row['username'];
        $email = $row['email'];
		$picture = $row['profilepicture'];
    }else{
        echo "There was an error retrieving the username and email from the database";
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
   
    <title>Profile</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styling.css" rel="stylesheet">  
      
    <!--Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Arvo&display=swap" rel="stylesheet">
      
    <style>
        #container{
            margin-top: 100px;
        }  
        
        .buttons{
            margin-bottom: 20px;
        }

        tr{
            cursor: pointer;
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
                        <li class="active" ><a href="#">Profile</a></li>
                        <li><a href="#">Help</a></li>
                        <li><a href="#">Contact us</a></li>
                        <li><a  href="mainpageloggedin.php">My Trips</a></li>
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
                <div class="col-md-offset-3 col-md-6">
                    <h2>General Account Settings:</h2>
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed table-bordered">
                            <tr data-target="#updateUsername" data-toggle="modal">
                               <td>Username</td> 
                               <td><?php echo $username; ?></td>
                            </tr>
                            <tr data-target="#updateEmail" data-toggle="modal">
                               <td>Email</td> 
                               <td><?php echo $email; ?></td>
                            </tr>
                            <tr data-target="#updatePassword" data-toggle="modal">
                               <td>Password</td> 
                               <td>***********</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <!-- Update Username -->
    <form  method="post" id="updateUsernameForm">
        <div class="modal" id="updateUsername" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4>Edit Username:</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Update username message from PHP file-->
                        <div id="updateUsernameMessage">

                        </div>
                        <!-- UserName input -->
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input class="form-control" type="text" name="username" id="username" maxlength="30" value="<?php echo $username; ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input class="btn green" name="updateUsername" type="submit" value="Submit">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Update Email -->
    <form  method="post" id="updateEmailForm">
        <div class="modal" id="updateEmail" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4>Enter new email:</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Update email message from PHP file-->
                        <div id="updateEmailMessage">

                        </div>
                        <!-- Email input -->
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input class="form-control" type="email" name="email" id="email" maxlength="50" value="<?php echo $email; ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input class="btn green" name="updateEmail" type="submit" value="Submit">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Update Password -->
    <form  method="post" id="updatePasswordForm">
        <div class="modal" id="updatePassword" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4>Enter Current and New password:</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Update Password message from PHP file-->
                        <div id="updatePasswordMessage">

                        </div>
                        <!-- Password inputs -->
                        <div class="form-group">
                            <label for="currentPassword" class="sr-only">Your Current Password:</label>
                            <input class="form-control" type="password" name="currentPassword" id="currentPassword" maxlength="30" placeholder="Your Current Password">
                        </div>
                        <div class="form-group">
                            <label for="newPassword" class="sr-only">Your New Password:</label>
                            <input class="form-control" type="password" name="newPassword" id="newPassword" maxlength="30" placeholder="Choose a password">
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword" class="sr-only">Confirm New Password:</label>
                            <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" maxlength="30" placeholder="Confirm password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input class="btn green" name="updatePassword" type="submit" value="Submit">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

	<!-- Update Picture -->
    <form  method="post" id="updatePictureForm" enctype="multipart/form-data">
        <div class="modal" id="updatePicture" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
					<!--modal header-->
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4>Upload Picture:</h4>
                    </div>
					<!--modal body-->
                    <div class="modal-body">
                        <!-- Update profile picture message from PHP file-->
                        <div id="updatePictureMessage">

                        </div>
                       
						<div>
							<?php
								if(empty($picture)){
									echo "<img id='profilePicturePreview' style='height:auto; max-width: 100%; border-radius: 50%;' src='images/noimage.jpg'/>";
								}else{
									echo "<img id='profilePicturePreview' style='height:auto; max-width: 100%; border-radius: 50%;' src='$picture'/>";
								}
							?>
						</div>
						
						<div class="form-group">
							<label for="picture">Select a picture:</label>
							<input type="file" name="picture" id="picture">
						</div>
                    </div>
					<!--modal footer-->
                    <div class="modal-footer">
                        <input class="btn green" name="updateUsername" type="submit" value="Submit">
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

   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-3.5.1.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Ajax script-->
    <script src="js/profile.js"></script>
  </body>
</html>