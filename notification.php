<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// This file is responsible for inserting, deleting, updating and selecting all notifications from the server related to the current user

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



// NOTE Execute Insert Method
if ($_REQUEST['action'] == 'insert') {

    // Checking did the file received all necessary information
    if (!isset($_REQUEST['byUser_id']) || !isset($_REQUEST['user_id']) || !isset($_REQUEST['type'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        echo json_encode($return);
        return;
    }

    // casting received values and converting them to the variables
    $byUser_id = htmlentities($_REQUEST['byUser_id']);
    $user_id = htmlentities($_REQUEST['user_id']);
    $type = htmlentities($_REQUEST['type']);


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

    // NOTE Execute Delete Method
} else if ($_REQUEST['action'] == 'delete') {

    // Checking did the file received all necessary information
    if (!isset($_REQUEST['byUser_id']) || !isset($_REQUEST['user_id']) || !isset($_REQUEST['type'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        echo json_encode($return);
        return;
    }

    // casting received values and converting them to the variables
    $byUser_id = htmlentities($_REQUEST['byUser_id']);
    $user_id = htmlentities($_REQUEST['user_id']);
    $type = htmlentities($_REQUEST['type']);


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

    // NOTE Execute Select Method
} else if ($_REQUEST['action'] == 'select') {

    // checking for receiving limit and offset values
    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['limit']) || !isset($_REQUEST['offset'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        $access->disconnect();
        echo json_encode($return);
        return;
    }

    // safe mode of casting limit and access
    $user_id = htmlentities($_REQUEST['user_id']);
    $limit = htmlentities($_REQUEST['limit']);
    $offset = htmlentities($_REQUEST['offset']);

    // assign all selected notifications to $notifications var
    $notifications = $access->selectNotifications($user_id, $limit, $offset);

    // selected
    if ($notifications) {
        // $return['notifications'] = $notifications;
        $return = $notifications;

        // could not select
    } else {
        $return['status'] = '400';
        $return['message'] = 'No notifications are selected';
    }

    // NOTE Execute Update Method
} else if ($_REQUEST['action'] == 'update') {

    // checking for receiving limit and offset values
    if (!isset($_REQUEST['id']) || !isset($_REQUEST['viewed'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        $access->disconnect();
        echo json_encode($return);
        return;
    }

    // safe mode of casting limit and access
    $id = htmlentities($_REQUEST['id']);
    $viewed = htmlentities($_REQUEST['viewed']);

    // run insert function
    $result = $access->updateNotification($viewed, $id);

    // inserted
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Notification is updated.';

        // failed
    } else {
        $return['status'] = '400';
        $return['message'] = 'Notification could not be updated.';
    }
}

// STEP 4. Disconnect and provide user with the JSON information.
echo json_encode($return);
$access->disconnect();
