<?php
    // This file receives the user_id and key generated to create the new password
    // This file displays a form to input new password
    session_start();
    include('connection.php');
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset</title>

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
            <h1>Reset Password:</h1>
            <div id="resultMessage"></div>
            <?php
            // If user_id or reset_key is missing
            if(!isset($_GET['user_id']) || !isset($_GET['key'])){
                echo '<div class="alert alert-danger">There was an error. Please click on the reset link you received by email.</div>';
                exit;
            }
            // else
            //     Store them in two variables
            $user_id = $_GET['user_id'];
            $key = $_GET['key'];

            // check the time past 24hours, because key valid only for 24 hours
            $time = time() - 86400;

            //     Prepare variables for the query
            $user_id = mysqli_real_escape_string($connect,$user_id);
            $key = mysqli_real_escape_string($connect,$key);

            //     Run query: Check combination of user_id & key exists and less than 24h old, and check key if have been used
            $sql = "SELECT `user_id` FROM forgotpassword WHERE `reset_key`='$key' AND 
                                           `user_id`='$user_id' AND `time` > '$time' AND `status`='pending'";

            $result = mysqli_query($connect,$sql);

            if(!$result){
                echo '<div class="alert alert-danger">Error running the query!</div>';
                exit;
            }

            // If combination does not exist
            // show an error message
            $count = mysqli_num_rows($result);
            if($count != 1){
                echo '<div class="alert alert-danger">Please try again.</div>';
                exit; // stop
            }

            // print reset password form with hidden user_id and key fields
            echo "
            <form method='post' id='passwordReset' >
            <input type='hidden'name='key' value='$key'>
            <input type='hidden'name='user_id' value='$user_id'>
                <div class='form-group'>
                <label for='password'>Enter your new Password:</label>
                    <input type='password' name='password' id='password' placeholder='Enter Password' class='form-control'>  
                </div>
                <div class='form-group'>
                <label for='password2'>Re-enter Password:</label>
                    <input type='password' name='password2' id='password2' placeholder='Re-enter Password' class='form-control'>  
                </div>
                <input type='submit' name='resetpassword' class='btn btn-success btn-lg' value='Reset Password'>
            </form>
            ";
            ?>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
    <!-- Script for Ajax Call to storeresetpassword.php which processes form data-->
            <script>
                // Once the form is submitted
                $("#passwordReset").submit(function(event){
                    // prevent default php processing
                    event.preventDefault();

                    // collect user input
                    var datapost = $(this).serializeArray();

                    // send them to forgot-password.php using AJAX
                    $.ajax({
                        url: "storeresetpassword.php",
                        type: "POST",
                        data: datapost,
                        success: function(data){
                            // AJAX Call successful: show error or success message
                            $('#resultMessage').html(data);
                        },
                        // AJAX Call fails: show Ajax Call error
                        error: function(){
                            $("#resultMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
                        }
                    });
                });

            </script>
</body>
</html>
<?php
// we need close a connection, if its still opened
if (isset($connect)) {
    mysqli_close($connect);
}
?>
