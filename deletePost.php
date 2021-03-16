<?php

// Deleting the post and all post related information

// STEP 1. Verification of the data received to this file
if (!isset($_REQUEST['id'])) {
    $return['status'] = '400';
    $return['message'] = 'Missing required information.';
    echo json_encode($return);
    return;
}

// encode received variables / information
$id = htmlentities($_REQUEST['id']);

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

// STEP 3. Delete post from the server
$result = $access->deletePost($id);

// deleted successfully
if ($result) {
    $return['status'] = '200';
    $return['messsage'] = 'Post has been deleted successfully.';
    $return['deleted'] = $result;

    // could not delete
} else {

    $return['status'] = '400';
    $return['message'] = 'Could not delete the post.';
    $return['deleted'] = $result;
}

// disconnect from the server 
$access->disconnect();

// throw back json to the user
echo json_encode($return);
