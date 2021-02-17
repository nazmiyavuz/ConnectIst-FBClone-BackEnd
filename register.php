<?php


// This class is in charge of sending new user (registartion) information to the server

// STEP 1. Receiving data passed to current PHP file. Executing IF statement. 
// If not all data has been passed, return / stop execution by throwing JSON message
if (
    empty($_REQUEST['email']) || empty($_REQUEST['password']) ||
    empty($_REQUEST['userName']) || empty($_REQUEST['fullName'])
) {

    $return['status'] = '400';
    $return['message'] = 'Missing user information';
    echo json_encode($return);
    return;
}

// using safe method to cast received data in current PHP 
$email = htmlentities($_REQUEST['email']);
$password = htmlentities($_REQUEST['password']);
$userName = htmlentities($_REQUEST['userName']);
$fullName = htmlentities($_REQUEST['fullName']);

// generating random 20 chars pseudo
$salt = openssl_random_pseudo_bytes(20);
$secured_password = sha1($password . $salt);

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

// STEP 3. Check availability of the login / user information
$user = $access->selectUser($email, $userName);

// found user with the same Email address
if (!empty($user)) {

    // throw back JSON to user
    $return['status'] = '400';
    $return['message'] = 'The Email or Username is already registered';
    echo json_encode($return);

    // disconnect from the server and stop executing of current PHP
    $access->disconnect();
    return;
} else {


    // STEP 4. Send request to Insert the data in the server
    $result = $access->registertUser($email, $secured_password, $salt, $userName,  $fullName);

    // result is positive - inserted
    if ($result) {

        // select currently inserted user
        $user = $access->selectUser($email, $userName);

        // throw back the user details
        $return['status'] = '200';
        $return['message'] = 'Successfully registered';
        $return['id'] = $user['id'];
        $return['email'] = $email;
        $return['userName'] = $userName;
        $return['fullName'] = $fullName;
        $return['ava'] = $user['ava'];
        echo json_encode($return);

        $access->disconnect();
        return;

        // result is negative - couldn't insert
    } else {

        $return['status'] = '400';
        $return['message'] = 'Could not insert information';
        echo json_encode($return);

        $access->disconnect();
        return;
    }
}
