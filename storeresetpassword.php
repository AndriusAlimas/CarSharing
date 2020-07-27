<!-- This file receives:user_id, generated key to reset password, password1 and password2-->
<!-- This file then resets password for user_id if all checks are correct-->
<?php
        session_start();
        include('connection.php');

            if(!isset($_POST['user_id']) || !isset($_POST['key'])){
                echo '<div class="alert alert-danger">There was an error. Please click on the reset link you received by email.</div>';
                exit;
            }
            // else
            //     Store them in two variables
            $user_id = $_POST['user_id'];
            $key = $_POST['key'];

            // check the time past 24hours, because key valid only for 24 hours
            $time = time() - 86400;

            //     Prepare variables for the query
            $user_id = mysqli_real_escape_string($connect,$user_id);
            $key = mysqli_real_escape_string($connect,$key);

            //     Run query: Check combination of user_id & key exists and less than 24h old, and check key if have been used
            $sql = "SELECT `user_id` FROM forgotpassword WHERE `reset_key`='$key' 
                                       AND `user_id`='$user_id' AND `time` > '$time' AND `status`='pending'";

            $result = mysqli_query($connect,$sql);

            if(!$result){
                echo '<div class="alert alert-danger">Key expired or have been already used!</div>';
                exit;
            }

            // If combination does not exist
            // show an error message
            $count = mysqli_num_rows($result);
            if($count != 1){
                echo '<div class="alert alert-danger">Key expired or have been already used!.</div>';
                exit; // stop
            }

            //  Define errors messages
                $missingPassword = '<p><strong>Please enter a Password!<strong></p>';
                $invalidPassword = '<p><strong>Your passsword should be at least 6 characters
                    long and include one capital letter and one number!<strong></p>';
                $differentPassword = '<p><strong>Password don\'t match!<strong></p>';
                $missingPassword2  = '<p><strong>Please confirm your password<strong></p>';

                // result message:
                $errors ="";

                // GET PASSWORDS
                if(empty($_POST["password"])){
                    $errors .= $missingPassword;
                    // check password length is not less than 6 characters and password include at least one capital letter
                }elseif(!(strlen($_POST["password"])>5 and preg_match('/[A-Z]/',$_POST["password"])
                    and preg_match('/[0-9]/',$_POST["password"])  // password must include at least one number
                )
                ){
                    $errors .= $invalidPassword;
                }else{
                    $password = filter_var($_POST["password"],FILTER_SANITIZE_STRING);

                    // password2 is missing?
                    if(empty($_POST["password2"])){
                        $errors .= $missingPassword2;
                    }else{
                        $password2 = filter_var($_POST["password2"],FILTER_SANITIZE_STRING);

                        // password2 are same as password?
                        if(strcmp($password,$password2)){
                            $errors .= $differentPassword;
                        }
                    }
                }

            //  If there are any errors print error
                if($errors){
                    $resultMessage = '<div class="alert alert-danger">' . $errors. '</div>';
                    echo $resultMessage;
                    exit;
                }

                // No errors
                //     Prepare password for the queries
                    $password = mysqli_real_escape_string($connect,$password);
                    $password = hash('sha256',$password);

                // Prepare user_id for the queries
                    $user_id =  mysqli_real_escape_string($connect,$user_id);

                // Run Query: Update users password in the users table
                    $sql = "UPDATE `users` SET `password`='$password' WHERE `user_id`='$user_id'";
                    $result = mysqli_query($connect,$sql);

                if(!$result){
                    echo '<div class="alert alert-danger">There was an error storing a new password into database! </div>';
                    exit;
                }

                // set the key status to 'used' in the forgotpassword table
                // prevent the key from being used twice
                $sql ="UPDATE `forgotpassword` SET `status`='used' WHERE `reset_key`='$key' AND `user_id`='$user_id'";
                $result = mysqli_query($connect,$sql);

                    if(!$result){
                        echo '<div class="alert alert-danger">Error running the query!</div>';
                    }else{
                        echo '<div class="alert alert-success">Your password has been update successfully! 
                                <a href="index.php"> Login</a> </div>';
                    }

// we need close a connection, if its still opened
if (isset($connect)) {
    mysqli_close($connect);
}
?>