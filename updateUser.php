<?php

// This file is responsible for updating user related information in the server

// STEP 1. Receiving passed information to current file
if (empty($_REQUEST['id']) || empty($_REQUEST['email']) || empty($_REQUEST['userName']) || empty($_REQUEST['fullName'])) {
    $return['status'] = '400';
    $return['message'] = 'Missing required information for update user';
    echo json_encode($return);
    return;
}

// safe method of casting received values.
$id = htmlentities($_REQUEST['id']);
$email = htmlentities($_REQUEST['email']);
$userName = htmlentities($_REQUEST['userName']);
$fullName = htmlentities($_REQUEST['fullName']);

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
$access->connect();

// STEP 3. Update user information
$result = $access->updateUser($email, $userName, $fullName, $id);

if ($result) {

    // updated successfully
    $return['status'] = '200';
    $return['message'] = 'User is updated successfully';

    // STEP 4. Update passowrd
    if ($_REQUEST['newPassword'] == 'true') {

        // receiving new password. generating new salt. generating new Super Securerd password
        $password = htmlentities($_REQUEST['password']);
        $salt = openssl_random_pseudo_bytes(20);
        $dbpassword = sha1($password . $salt);

        // updating password in database
        $passwordChanged = $access->updatePassword($id, $dbpassword, $salt);

        // logic of secenerios
        if ($passwordChanged) {
            $return['password'] = 'Password is changed successfully';
        } else {
            $return['password'] = 'Password could not be changed';
        }
    }

    // STEP 5. Return back user related information
    $user = $access->selectUserID($id);

    // logic of secenerios
    if ($user) {
        $return['id'] = $user['id'];
        $return['email'] = $user['email'];
        $return['userName'] = $user['userName'];
        $return['fullName'] = $user['fullName'];
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not complete the process';
    }
} else {

    // can not update
    $return['status'] = '400';
    $return['message'] = 'Could not update user';
}

// STEP 7. Shut down
$access->disconnect();
echo json_encode($return);
