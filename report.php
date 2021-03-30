<?php

// responsible for inserting reprts and complaints into database

// STEP 1. Check passed inf to this PHP file
if (!isset($_REQUEST['post_id']) || !isset($_REQUEST['user_id']) || !isset($_REQUEST['reason']) || !isset($_REQUEST['byUser_id'])) {
    $return['status'] = '400';
    $return['message'] = 'Missing required information';
    echo json_encode(($return));
    return;
}

$post_id = htmlentities($_REQUEST['post_id']);
$user_id = htmlentities($_REQUEST['user_id']);
$reason = htmlentities($_REQUEST['reason']);
$byUser_id = htmlentities($_REQUEST['byUser_id']);

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

// STEP 3. Insert Complaint 
$result = $access->insertReport($post_id, $user_id, $reason, $byUser_id);

// check the status of execution.
if ($result) {
    $return['status'] = '200';
    $return['message'] = 'Reported successfully.';
} else {
    $return['status'] = '400';
    $return['message'] = 'Error while sending your feedback.';
}

// STEP 4. Disconnect and provide user with the JSON information.
echo json_encode($return);
$access->disconnect();
