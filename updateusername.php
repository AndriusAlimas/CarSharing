<?php
session_start();
    if(empty($_POST['username'])){
        echo "<div class='alert alert-warning'>
                    Please enter any username!
              </div>";
        exit;
    }else{
        // Get username sent through Ajax:
        $username = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
        include ("connection.php");
        $username = mysqli_real_escape_string($connect,$username);
        // get user_id
        $id = $_SESSION['user_id']; // this is where we store in login.php
    }

    // Run query and update username
        $sql = "UPDATE `users` SET `username`='$username' WHERE `user_id`='$id'";

        $result = mysqli_query($connect,$sql);

        if(!$result){
            echo '<div class="alert alert-danger">There was an error updating storing the new username in the database!</div>';
        }
        if(isset($connect)){
            mysqli_close($connect);
        }
?>