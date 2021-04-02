<?php

// This file sends request to the server for retreiving all posts related to the certain user.

// STEP 1. Receive passed variables / information
if (!isset($_REQUEST['id']) && !isset($_REQUEST['limit']) && !isset($_REQUEST['offset'])) {
    $return['status'] = '400';
    $return['message'] = 'Missing required information.';
    echo json_encode($return);
    return;
}

// encode received variables / information
$id = htmlentities($_REQUEST['id']);
$limit = htmlentities($_REQUEST['limit']);
$offset = htmlentities($_REQUEST['offset']);



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

// STEP 3. Select posts from the server
// If action is set already and is equal to FEED, then exec-te the function to load the feed, or load the normal posts of the user
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'feed') {
    $posts = $access->selectPostsForFeed($id, $offset, $limit);
} else {
    $posts = $access->selectPosts($id, $offset, $limit);
}


// found posts / could not found
if ($posts) {
    $return = $posts;
} else {
    $return['message'] = 'Could not find posts.';
}


// STEP 4. Disconnect =>  ^  => $return['posts'] = $posts;
echo json_encode($return);
$access->disconnect();
