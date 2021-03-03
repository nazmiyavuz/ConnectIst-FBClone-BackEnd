<?php

// This file is responsibler for liking and unliking (inserting like into DB and deleting Like from the DB)

// STEP 1. Receive valuies passed to current file
if (empty($_REQUEST['post_id']) || empty($_REQUEST['user_id'])) {
    $return['status'] = '400';
    $return['message'] = 'Missing required information';
    echo json_encode(($return));
    return;
}

// secure mthod of receiving values passed to current file.
$post_id = htmlentities($_REQUEST['post_id']);
$user_id = htmlentities($_REQUEST['user_id']);

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

// STEP 3. Insert or Delete Like
// Insert 
if ($_REQUEST['action'] == "insert") {

    // run protocol from Access.php that insert like
    $result = $access->insertLike($post_id, $user_id);

    // Liked successfully
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Like has been registered successfully';

        // Could not Insert Like
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not register Like';
    }

    // Delete
} else {

    // run protocol from Access.php that deletes like
    $result = $access->deleteLike($post_id, $user_id);

    // Deleted successfully
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Like has been deleted successfully';

        // Could not Delete Like
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not remove Like';
    }
}

// STEP 4. Close connecton
echo json_encode($return);
$access->disconnect();
