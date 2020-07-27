<?php
	$user_id = $_SESSION['user_id'];

	// get username
	$sql = "SELECT * FROM `users` WHERE `user_id`='$user_id'";
	
	include("connection.php");

	$result = mysqli_query($connect,$sql);

	$count = mysqli_num_rows($result);

	if($count == 1){
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$username = $row["username"];
		$picture = $row['profilepicture'];
	}else{
		echo "<div class='alert alert-danger'>There was an error retrieving the username from the database.</div>";
	}
?>
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
                        <li class="active"><a href="#">Search</a></li>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="#">Help</a></li>
                        <li><a href="#">Contact us</a></li>
                        <li><a href="mainpageloggedin.php">My Trips</a></li>
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