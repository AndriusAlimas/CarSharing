<?php
    // Start session 
    session_start();

    //Connect to the database 
   include('connection.php');

//  Define errors messages
    $missingUserName = '<p><strong>Please enter a User Name!<strong></p>';
    $missingEmail = '<p><strong>Please enter your email address!<strong></p>';
    $invalidEmail = '<p><strong>Please enter a valid Email Address!<strong></p>';
    $missingPassword = '<p><strong>Please enter a Password!<strong></p>';
    $invalidPassword = '<p><strong>Your passsword should be at least 6 characters
    long and include one capital letter and one number!<strong></p>';
    $differentPassword = '<p><strong>Password don\'t match!<strong></p>';
    $missingPassword2  = '<p><strong>Please confirm your password!<strong></p>';
    $missingFirstName  = '<p><strong>Please enter your First Name!<strong></p>';
    $missingLastName  = '<p><strong>Please enter your Last Name!<strong></p>';
    $missingPhone  = '<p><strong>Please enter your phone number!<strong></p>';
    $invalidPhoneNumber  = '<p><strong>Please enter a valid phone number(digits only and less than 15 long)!<strong></p>';
	$missingGender = "<p><strong>Please select your gender!</strong></p>";
	$missingInformation = "<p><strong>Please share a few more words about yourself.</strong></p>";
	
    // result message:
    $errors ="";

    //  Check user inputs
  // Store errors in errors variable 
            //  Get inputs values:
				$username = $_POST["username"];
				$firstName = $_POST["firstName"];
				$email = $_POST["signupEmail"];
				$password = $_POST["signupPassword"];
				$password2 = $_POST["signupPasswordR"];
				$lastName = $_POST["lastName"];
				$phone = $_POST["phoneNumber"];
				$moreInformation = $_POST["moreInformation"];

                // username validate and sanitized
                    if(empty($username)){
                        $errors .= $missingUserName;
                    }else{
                        $username = filterIt($username,$connect,true);
                    }

				// firstName validate and sanitized
					if(empty($firstName)){
						$errors .= $missingFirstName;
					}else{
						$firstName = filterIt($firstName,$connect,true);
					}
				
				// lastName validate and sanitized
					if(empty($lastName)){
						$errors .= $missingLastName;
					}else{
						$lastName = filterIt($lastName,$connect,true);
					}

                // email validate and sanitized
                    if(empty($email)){
                        $errors .= $missingEmail;
                    }else{
                        $email = filterIt($email,$connect,false);
                    }

                // password validate and sanitized
                    if(empty($password)){
                        $errors .= $missingPassword;
                      // check password length is not less than 6 characters and password include at least one capital letter   
                    }elseif(!(strlen($password)>5 and preg_match('/[A-Z]/',$_POST["signupPassword"])
                           and preg_match('/[0-9]/',$password)  // password must include at least one number
                           )   
                    ){ 
                        $errors .= $invalidPassword;
                    }else{
                        $password = filterIt($password,$connect,true);
                        
                        // password2 is missing validate and sanitized
                        if(empty($password2)){
                            $errors .= $missingPassword2;
                        }else{
                            $password2 = filterIt($password2,$connect,true);

                            // password2 are same as password?
                            if(strcmp($password,$password2)){
                                $errors .= $differentPassword;
                            }
                        }
                    }

				  // phone number validate and sanitized
					if(empty($phone)){
						$errors .= $missingPhone;  
					}elseif(preg_match('/\D/',$phone)){
						$errors .= $invalidPhoneNumber;
					}else{
						$phone = filterIt($phone,$connect,true);
					}

				  // gender validate and sanitized	
					if(empty($_POST["gender"])){
						$errors .= $missingGender;
					}else{
						$gender = $_POST["gender"];
					}

				// more information validate and sanitized	
					if(empty($moreInformation)){
						$errors .= $missingInformation;
					}else{
						$moreInformation = filterIt($moreInformation,$connect,true);
					}

				// If there are any errors print error 
                        if($errors){
                            $resultMessage = '<div class="alert alert-danger">' . $errors. '</div>';
                            echo $resultMessage;
                            exit;
                        }

				$password = hash('sha256',$password); // hash password

// No errors, Check and Lets PUT THEM INTO DATABASE
                                          
		// Check UserName with UserName Email:
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($connect,$sql);

        if(!$result){
            echo '<div class="alert alert-danger">Error running the query!</div>';
            exit;
        }

        $results = mysqli_num_rows($result);
        if($results){
            echo '<div class="alert alert-danger">That username is already registered. Do you want to log in?</div>';
            exit; // stop
        }

//       else  If email exists in the users table print error 
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($connect,$sql);

        if(!$result){
            echo '<div class="alert alert-danger">Error running the query!</div>';
            exit;
        }

        $results = mysqli_num_rows($result);
        if($results){
            echo '<div class="alert alert-danger">That email is already registered. Do you want to log in?</div>';
            exit;
        }

			//  Create a unique activation code
                    $activationKey = bin2hex(openssl_random_pseudo_bytes(16));
       
	//  Insert user details and activation code in the users table
            $sql = "INSERT INTO `users` (
                `username`, `email`, `password`, `activation`, `first_name`, `last_name`, `phonenumber`, `gender`, `moreInformation`) VALUES('$username', '$email', '$password', '$activationKey', '$firstName', '$lastName', '$phone', '$gender', '$moreInformation')";

              $result = mysqli_query($connect,$sql);
        
              if(!$result){
                   echo '<div class="alert alert-danger">There was an error inserting the users details in the database! </div>';
                   echo '<div class="alert alert-danger">'. mysqli_error($connect) .'</div>';
                  exit;
              }

//      Send the user an email with a link to activate. php with their email and activation code
        $message = "Please click on this link to activate your account:\n\n";
        $message .= "https://alimas.host20.uk/WEB/13.Car%20Sharing%20(JS,PHP,MySQL,AJAX,JSON)/activate.php?email=" .
            urlencode($email) ."&key=$activationKey";

        if(mail($email, 'Confirm your Registration',$message,'From:'.'andriusjavait@gmail.com')){
           echo '<div class="alert alert-success">Thank for your registring! A confirmation email has been sent to '. $email . '. Please click on the activation link to activate your account.</div>';
        }

// we need close a connection, if its still opened
if (isset($connect)) {
    mysqli_close($connect);
}

include('filter.php');
?>

