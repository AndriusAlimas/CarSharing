<?php
//The user is re-directed to this file after clicking the  link received by
// email and aiming at proving they own the new email address
// link contains three GET parameters: email, newEmail and activation key
session_start(); // resume previous session
include("connection.php");
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Email activation</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        h1{
            color: purple;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-offset-1 col-sm-10 contactForm">
            <h1>Email Activation</h1>
            <?php
            // If email,newEmail or activation key is missing show an error->
            if(!isset($_GET['email']) || !isset($_GET['newEmail']) || !isset($_GET['key'])){
                echo '<div class="alert alert-danger">There was an error. Please click on the link you received by email.</div>';
                exit;
            }
            // else
            //     Store them in three variables
            $email = $_GET['email'];
            $newEmail = $_GET['newEmail'];
            $key = $_GET['key'];

            //     Prepare variables for the query
            $email = mysqli_real_escape_string($connect,$email);
            $newEmail = mysqli_real_escape_string($connect,$newEmail);
            $key = mysqli_real_escape_string($connect,$key);

            //     Run query: update email
            $sql = "UPDATE `users` SET `email`='$newEmail', `activation2`='0' WHERE (`email`='$email' AND `activation2`='$key') LIMIT 1";

            mysqli_query($connect,$sql);

            //     If query is successful , show success message
            if(mysqli_affected_rows($connect) == 1){
                session_destroy();
                setcookie("rememberme","",time() - 3600);
                echo '<div class="alert alert-success">Your email has been updated.</div>';
                echo '<a href="index.php" type="button" class="btn btn-lg btn-success">Log in</a>';
            }else{
//         Show error message
                echo '<div class="alert alert-danger">Your email could not be updated. Please try again later.</div>';
                echo '<div class="alert alert-danger">'. mysqli_error($connect) . '</div>';
            }
            ?>

        </div>
    </div>
</div>
<?php
// we need close a connection, if its still opened
if (isset($connect)) {
    mysqli_close($connect);
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>