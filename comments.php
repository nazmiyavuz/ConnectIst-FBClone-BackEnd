<?php

// this file is responsible for inserting / deleting / showing comments to the users


// STEP 1. Build connection
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

// declare array to store all information for throwing back to the user
$return = array();

// STEP 3. Insert / Delete / Show comments
if ($_REQUEST['action'] == 'insert') {

    if (empty($_REQUEST['post_id']) || empty($_REQUEST['user_id']) || empty($_REQUEST['comment'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information for insert';
        echo json_encode(($return));
        $access->disconnect();
        return;
    }

    // secure method of receiving values passed to current file.
    $post_id = htmlentities($_REQUEST['post_id']);
    $user_id = htmlentities($_REQUEST['user_id']);
    $comment = htmlentities($_REQUEST['comment']);

    // run function to insert the comment and assign the result of executed function to $result var
    $result = $access->insertComment($post_id, $user_id, $comment);

    // if the result is positive (it means the comment successfully), else -> failed
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Commented successfully';

        // Could not Insert Like
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not comment';
    }

    // selecting the comments
} else if ($_REQUEST['action'] == 'select') {

    // checking for the existance of id or limit or offset information
    if (empty($_REQUEST['post_id']) || empty($_REQUEST['limit']) || !isset($_REQUEST['offset'])) {

        $return['status'] = '400';
        $return['message'] = 'Missing required information for select';
        echo json_encode(($return));
        $access->disconnect();
        return;
    }

    // safe mode of casting received data / information
    $post_id = htmlentities($_REQUEST['post_id']);
    $limit = htmlentities($_REQUEST['limit']);
    $offset = htmlentities($_REQUEST['offset']);

    // execute function that selects all comments of the post and assign all resul;ts to $comments
    $comments = $access->selectComments($post_id, $limit, $offset);

    // selected successfully
    if ($comments) {

        $return = $comments;
    }
}

// STEP 4. Close connecton
echo json_encode($return);
$access->disconnect();
