<?php

session_start(); // start or restart seesion

// Connect to the database 
    include("connection.php");

// Check user inputs 
//         Define error messages
            $missingEmail = '<p><strong>Please enter your email address!</strong></p>';
            $missingPassword = '<p><strong>Please enter your password!</strong></p>';
			$errors= "";
//         Get email and password
                // EMAIL:
              if(empty($_POST["loginEmail"])){
                        $errors .= $missingEmail; //  Store errors in errors variable 
                    }else{
                        $email = filter_var($_POST["loginEmail"],FILTER_SANITIZE_EMAIL);
                    }

                // PASSWORD:
                       if(empty($_POST["loginPassword"])){
                            $errors .= $missingPassword; //  Store errors in errors variable 
                        }else{
                            $password = filter_var($_POST["loginPassword"],FILTER_SANITIZE_STRING);
                        }

                // If there are any errors print error message 
                    if($errors){
                          $resultMessage = '<div class="alert alert-danger">' . $errors. '</div>';
                            echo $resultMessage;
                        }else{
                //else: No errors
                        //     Prepare variables for the queries 
                         $email = mysqli_real_escape_string($connect,$email);
                         $password = mysqli_real_escape_string($connect,$password);
                         $password = hash('sha256',$password); // when you sign up, it was hash sha256, so we need to use same algorithm to get same result
                             
                         //     Run query: Check combination of email & password exists
                        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND activation='activated'";
                        $result = mysqli_query($connect,$sql);
                       
                        // check if query runs successfully
                              if(!$result){
                                  echo '<div class="alert alert-danger">Error running the query!</div>';
                                  echo '<div class="alert alert-danger">'. mysqli_error($connect) .'</div>';
                            exit;
                         }
                        
                        //     If email & password don't match print error
                        $count = mysqli_num_rows($result);
                        if($count !== 1){
                               echo '<div class="alert alert-danger">Wrong Username or Password</div>';
                        }
                        else{
                            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

                            //         log the user in: Set session variables
                            $_SESSION['user_id'] = $row['user_id'];
                            $_SESSION['username'] = $row['username'];
                            $_SESSION['email'] = $row['email'];
                        
                        
                        //         If remember me is not checked
                        if(empty($_POST['rememberMe'])){
                            echo "success"; //  print "success" 
                        }else{
                        // functions need it for authentications:
                            include("hashcookieauth.php");

                     //  Create two variables $authentificator1 and $authentificator2
                            $authentificator1 = bin2hex(openssl_random_pseudo_bytes(10));
                            // 2*2*...*2 n-18(18-bits)
                            // hex 16 (2 * 2 * 2 *2)  (80/4) = 20 characters need it
                            $authentificator2 = openssl_random_pseudo_bytes(20);
                            
                            //   Store them in a cookie 
                            $cookieValue = f1($authentificator1,$authentificator2);
                            setcookie("rememberme", $cookieValue, time() + 1296000 ); // 15 * 24 * 60 * 60 = 1296000 ( for 15 days)
                            
                            $f2authentificator2 = f2($authentificator2);
                            $user_id = $_SESSION['user_id'];
                            $expiration = date('Y-m-d H:i:s',time() + 1296000 );
                            
                            // Run query to store them in rememberme table
                            $sql = "INSERT INTO rememberme
                                    (authentificator1,f2authentificator2,user_id,expires)
                                    VALUES
                                    ('$authentificator1', '$f2authentificator2', '$user_id', '$expiration')";
                            
                            $result = mysqli_query($connect,$sql);
                            
                            // If query unsuccessful 
                            if(!$result){
                                //      print error 
                            echo '<div class="alert alert-danger">There was an error storing data to remember you next time.</div>';
                            echo '<div class="alert alert-danger">'. mysqli_error($connect) .'</div>';
                            exit;
                        }else{// else 
                                //     print "success"
                                echo "success"; // to manipulate with call back function oj ajax
                            }
                        }
                     }

               }

// we need close a connection, if its still opened
if (isset($connect)) {
    mysqli_close($connect);
}
?>