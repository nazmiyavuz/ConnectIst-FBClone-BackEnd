<?php

// This file is in charge of login Process by sending the info / data to the server and receiving the feedback / status from the server

// STEP 1. Receive data / info passed to current file
if (empty($_REQUEST['email']) || empty($_REQUEST['password'])) {
    $return['status'] = '400';
    $return['message'] = 'Missing required information';
    echo json_encode($return);
    return;
}

// securing received info / data from hackers or injections
$email = htmlentities($_REQUEST['email']);
$password = htmlentities($_REQUEST['password']);

// STEP 2. Build connection
// Secure way to build conn
$file = parse_ini_file("../../../connectIst.ini");


// store in php var inf from ini var
$dbhost = trim($file["dbhost"]);
$dbuser = trim($file["dbuser"]);
$dbpass = trim($file["dbpass"]);
$dbname = trim($file["dbname"]);

// include in php var inf from ini var
require("secure/access.php");
$access = new access($dbhost, $dbuser, $dbpass, $dbname);
// $access = new access('localhost', 'root', '', 'connectIst');

$access->connect();


// STEP 3. Check existance of the user. Try to fetch user with the same Email adress.
$user = $access->loginUser($email);


// user is found
if ($user) {

    // STEP 3.1 get encrypted password and salt from the server for validation 
    $encryptedPassword = $user['password'];
    $salt = $user['salt'];

    // STEP 3.2 compare entered password by user from app / website; encrypting password; comparong the result with the result stored in the server;
    if ($encryptedPassword == sha1($password . $salt)) {

        // preparing information to be throwing back to the user in JSON
        $return['status'] = '200';
        $return['message'] = 'Logged in successfully.';
        $return['id'] = $user['id'];
        $return['email'] = $user['email'];
        $return['userName'] = $user['userName'];
        $return['fullName'] = $user['fullName'];
        $return['cover'] = $user['cover'];
        $return['ava'] = $user['ava'];
        $return['bio'] = $user['bio'];


        // encyrpted password and salt do not match what user is entering as password
    } else {

        $return['status'] = '201';
        $return['message'] = 'Passwords do not match';
    }

    // user isn't found
} else {

    $return['status'] = '401';
    $return['message'] = 'User is not found';
}

// stop connection with the server
$access->disconnect();

// pass info as JSON
echo json_encode($return);
