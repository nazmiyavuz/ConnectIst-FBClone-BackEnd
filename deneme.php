<?php

// this file is responsible for inserting , deleting, updating and selecting all natifications from the server ralated to the current user

// STEP 1. Check passed inf to this PHP file
// Checking did the file received all necessary information
if (empty($_REQUEST['byUser_id']) || empty($_REQUEST['user_id']) || empty($_REQUEST['type'])) {
    $return['status'] = '400';
    $return['message'] = 'Missing required information';
    echo json_encode($return);
    return;
}

// casting received values and converting them to the variables
$byUser_id = htmlentities($_REQUEST['byUser_id']);
$user_id = htmlentities($_REQUEST['user_id']);
$type = htmlentities($_REQUEST['type']);

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


// Execute Insert Method
if ($_REQUEST['action'] == 'insert') {

    // run insert function
    $result = $access->insertNotification($byUser_id, $user_id, $type);

    // inserted
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Notification is inserted';

        // failed
    } else {
        $return['status'] = '400';
        $return['message'] = 'Notification could not be inserted';
    }

    // Execute Delete Method
} else if ($_REQUEST['action'] == 'delete') {

    // run delete function
    $result = $access->deleteNotification($byUser_id, $user_id, $type);

    // deleted
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Notification is deleted';

        // failed
    } else {
        $return['status'] = '400';
        $return['message'] = 'Notification could not be deleted';
    }

    // Execute Select Method
} else if ($_REQUEST['action'] == 'select') {

    // checking if the values have been passed or not to current php file
    if (!isset($_REQUEST['id']) || !isset($_REQUEST['limit']) || !isset($_REQUEST['offset'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information.';
        $access->disconnect();
        echo json_encode($return);
        return;
    }

    // secured way of receiving values
    $id = htmlentities($_REQUEST['id']);
    $limit = htmlentities($_REQUEST['limit']);
    $offset = htmlentities($_REQUEST['offset']);

    $notifications = $access->selectNotifications($id, $limit, $offset);

    if ($notifications) {

        // $return['requests'] = $requests;
        $return = $notifications;
    } else {

        $return['status'] = '400';
        $return['message'] = 'No notifications were found.';
    }
}

// STEP 4. Disconnect and provide user with the JSON information.
echo json_encode($return);
$access->disconnect();
