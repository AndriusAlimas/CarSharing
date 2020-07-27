<?php
    // start session and connect
    session_start();
    include ("connection.php");

    // define error message
    $missingCurrentPassword = '<p><strong>Please enter your Current Password!</strong></p>';
    $incorrectCurrentPassword = '<p><strong>The Password entered is incorrect!</strong></p>';
    $invalidPassword = '<p><strong>Your passsword should be at least 6 characters
        long and include one capital letter and one number!<strong></p>';
    $differentPassword = '<p><strong>Password don\'t match!<strong></p>';
    $missingPassword = '<p><strong>Please enter a new Password!<strong></p>';
    $missingPassword2  = '<p><strong>Please confirm your password.<strong></p>';
    $errors ="";

    // check for errors
    if(empty($_POST['currentPassword'])){
        $errors .=$missingCurrentPassword;
    }else{
        $currentPassword = $_POST['currentPassword'];
        $currentPassword = filter_var($currentPassword,FILTER_SANITIZE_STRING);
        $currentPassword = mysqli_real_escape_string($connect,$currentPassword);

        $currentPassword = hash('sha256',$currentPassword);

        // check if given password is correct
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT `password` FROM `users` WHERE `user_id`='$user_id'";

       $result = mysqli_query($connect,$sql);

       $count = mysqli_num_rows($result);

       if($count !== 1){
           echo "<div class='alert alert-danger'>There was a problem running query!</div>";
       }else{
           $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
           if($currentPassword != $row['password']){
               $errors .= $incorrectCurrentPassword;
           }
       }
    }

        if(empty($_POST["newPassword"])){
            $errors .= $missingPassword;
            // check password length is not less than 6 characters and password include at least one capital letter
        }elseif(!(strlen($_POST["newPassword"])>5 and preg_match('/[A-Z]/',$_POST["newPassword"])
            and preg_match('/[0-9]/',$_POST["newPassword"])  // password must include at least one number
        )
        ){
            $errors .= $invalidPassword;
        }else{
            $password = filter_var($_POST["newPassword"],FILTER_SANITIZE_STRING);

            if(empty($_POST["confirmPassword"])){
                $errors .= $missingPassword2;
            }else{
                $password2 = filter_var($_POST["confirmPassword"],FILTER_SANITIZE_STRING);

                // password2 are same as password?
                if(strcmp($password,$password2)){
                    $errors .= $differentPassword;
                }
            }
        }

    // if there is an error print error message
            if($errors){
                $resultMessage = '<div class="alert alert-danger">' . $errors. '</div>';
                echo $resultMessage;
                exit;
            }else{
                $password = mysqli_real_escape_string($connect,$password);

                $password = hash('sha256',$password);

                // else run query and update password
                $sql = "UPDATE `users` SET `password`='$password' WHERE `user_id`='$user_id'";

                $result = mysqli_query($connect,$sql);

                if(!$result){
                    echo '<div class="alert alert-danger">The password could not be reset. Please try again later.</div>';
                }else{
                    echo '<div class="alert alert-success">Your password has been update successfully.</div>';
                }
            }
?>