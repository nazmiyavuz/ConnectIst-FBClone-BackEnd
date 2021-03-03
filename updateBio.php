<?php

// this protocol is in charge of updating the Bio of the user and throving back to user related information

// STEP 1. Check passed inf to this PHP file

if (empty($_REQUEST['id']) || empty($_REQUEST['bio'])) {
    $return['status'] = '400';
    $return['message'] = 'Missing required information';
    echo json_encode(($return));
    return;
}

$id = htmlentities($_REQUEST['id']);
$bio = htmlentities($_REQUEST['bio']);

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

// STEP 3. Update Bio
$result = $access->updateBio($id, $bio);

// updated successfully
if ($result) {

    // select user to throw backto the user updated information
    $user = $access->selectUserID($id);

    // throw back updated user information
    if ($user) {
        $return['status'] = '200';
        $return['messsage'] = 'Bio has been updated';
        $return['id'] = $user['id'];
        $return['email'] = $user['email'];
        $return['userName'] = $user['userName'];
        $return['fullName'] = $user['fullName'];
        $return['cover'] = $user['cover'];
        $return['ava'] = $user['ava'];
        $return['bio'] = $user['bio'];
    } else {
        $return['status'] = '400';
        $return['messsage'] = 'Could not complete the process.';
    }


    // error while updating
} else {

    $return['status'] = '400';
    $return['messsage'] = 'Unable to update bio';
}

echo json_encode($return);
$access->disconnect();
