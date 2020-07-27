<?php

// If the user is not logged in & rememberme cookie exists
if(!isset($_SESSION['user_id']) && !empty($_COOKIE['rememberme'])){
//        array_key_exists('user_id',$_SESSION); you can use this one instead of isset to check session arrya and a key user_id
    include("hashcookieauth.php");
    // extract $authentificators 1&2 from the cookie:
    // we split in two chunks with function explode with delimiter ,
    // we got an array then we want two arrays one will be as its another we need to go back to binary
    // so we use hex2bin to come back to original form
    list($authentificator1,$authentificator2)= explode(',',$_COOKIE['rememberme']);
    $authentificator2 = hex2bin($authentificator2);
    $f2authentificator2 = hash('sha256',$authentificator2);

    // Look for authentificator1 in the rememberme table
    $sql = "SELECT * FROM rememberme WHERE authentificator1='$authentificator1'";

    // running query
    $result = mysqli_query($connect, $sql);

    if(!$result){ // if we got problems in query print them
        echo '<div class="alert alert-danger">There was an error running the query.</div>';
        echo '<div class="alert alert-danger">'. mysqli_error($connect) .'</div>';
        exit;
    }

    // we want get from table one row
    $count = mysqli_num_rows($result);

    if($count !== 1){
        // echo '<div class="alert alert-danger">Remember me process failed!</div>';
        setcookie("rememberme", "", time() - 60*60);
        header("location: index.php");
    }

    $row = mysqli_fetch_array($result,MYSQLI_ASSOC); // so we want get this single correct row

    // If authentificator2 does not match print error
    if(!hash_equals($row['f2authentificator2'],$f2authentificator2)){ // we comparing hash
        echo '<div class="alert alert-danger">Remember me process failed!</div>';
    }else{  // else
        // generate new authentificators
        // store them in cookie and rememberme table
        $authentificator1 = bin2hex(openssl_random_pseudo_bytes(10));
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
        }
        // Log the user in and redirect to notes page
        $_SESSION['user_id'] = $row['user_id'];
        header("location:mainpageloggedin.php");
    }
}

// we need close a connection, if its still opened
if (isset($connect)) {
    mysqli_close($connect);
}
?>