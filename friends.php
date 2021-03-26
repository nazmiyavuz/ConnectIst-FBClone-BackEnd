<?php

// responsible for selecting all users from the server or accepting friendship equest or deleting the friend and so on

// STEP 1. Establish connection
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


// STEP 2. Execute command
// Search users
if (($_REQUEST['action']) == 'search') {

    // checking if the values have been passed or not to current php file
    if (!isset($_REQUEST['name']) || !isset($_REQUEST['id']) || !isset($_REQUEST['limit']) || !isset($_REQUEST['offset'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information.';
        echo json_encode($return);
        return;
    }

    // secured way of receiving values
    $name = htmlentities($_REQUEST['name']);
    $id = htmlentities($_REQUEST['id']);
    $limit = htmlentities($_REQUEST['limit']);
    $offset = htmlentities($_REQUEST['offset']);

    // runc func to select all users
    $users = $access->selectUsers($name, $id, $limit, $offset);

    // if we got something
    if ($users) {

        // $return['users'] = $users;
        $return = $users;

        // nothing has been gotten
    } else {

        $return['status'] = '400';
        $return['message'] = 'No users were found.';
    }

    // request friendship
} else if (($_REQUEST['action']) == 'add') {

    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['friend_id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information.';
        echo json_encode($return);
        return;
    }

    $user_id = htmlentities($_REQUEST['user_id']);
    $friend_id = htmlentities($_REQUEST['friend_id']);

    $result = $access->insertRequest($user_id, $friend_id);

    // if we got something
    if ($result) {

        $return['status'] = '200';
        $return['message'] = 'Request has been sent successfully.';

        // nothing has been gotten
    } else {

        $return['status'] = '400';
        $return['message'] = 'Could not sent a request.';
    }

    // reject friendship request
} else if (($_REQUEST['action']) == 'reject') {

    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['friend_id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information.';
        echo json_encode($return);
        return;
    }

    $user_id = htmlentities($_REQUEST['user_id']);
    $friend_id = htmlentities($_REQUEST['friend_id']);

    $result = $access->deleteRequest($user_id, $friend_id);

    // if we got something
    if ($result) {

        $return['status'] = '200';
        $return['message'] = 'Request hes been rejected successfully.';

        // nothing has been gotten
    } else {

        $return['status'] = '400';
        $return['message'] = 'Could not reject a request.';
    }
} else if (($_REQUEST['action']) == 'requests') {

    // checking if the values have been passed or not to current php file
    if (!isset($_REQUEST['id']) || !isset($_REQUEST['limit']) || !isset($_REQUEST['offset'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information.';
        echo json_encode($return);
        return;
    }

    // secured way of receiving values
    $id = htmlentities($_REQUEST['id']);
    $limit = htmlentities($_REQUEST['limit']);
    $offset = htmlentities($_REQUEST['offset']);

    $requests = $access->selectRequests($id, $limit, $offset);

    if ($requests) {

        // $return['requests'] = $requests;
        $return = $requests;
    } else {

        $return['status'] = '400';
        $return['message'] = 'No requests were found.';
    }
} else if (($_REQUEST['action']) == 'confirm') {

    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['friend_id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information.';
        echo json_encode($return);
        return;
    }

    $user_id = htmlentities($_REQUEST['user_id']);
    $friend_id = htmlentities($_REQUEST['friend_id']);

    $result = $access->insertFriend($user_id, $friend_id);

    // Confirmed
    if ($result) {

        // delete request from requests table after it got confirmed (it goes to friends)
        $access->deleteRequest($user_id, $friend_id);

        $return['status'] = '200';
        $return['message'] = 'Friend is confirmed successfully.';


        // Failed to confirm
    } else {

        $return['status'] = '400';
        $return['message'] = 'Failed to confirm the friend.';
    }

    // Deleting Friend
} else if (($_REQUEST['action']) == 'delete') {

    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['friend_id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information.';
        echo json_encode($return);
        return;
    }

    $user_id = htmlentities($_REQUEST['user_id']);
    $friend_id = htmlentities($_REQUEST['friend_id']);

    $result = $access->deleteFriend($user_id, $friend_id);

    // if we got something
    if ($result) {

        $return['status'] = '200';
        $return['message'] = 'Friend has been deleted successfully.';

        // nothing has been gotten
    } else {

        $return['status'] = '400';
        $return['message'] = 'Could not delete a friend.';
    }
    // follow user
} else if (($_REQUEST['action']) == 'follow') {

    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['friend_id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information.';
        echo json_encode($return);
        return;
    }

    $user_id = htmlentities($_REQUEST['user_id']);
    $follow_id = htmlentities($_REQUEST['friend_id']);

    $result = $access->insertFollow($user_id, $follow_id);

    // if we got something
    if ($result) {

        $return['status'] = '200';
        $return['message'] = 'Started following successfully.';

        // nothing has been gotten
    } else {

        $return['status'] = '400';
        $return['message'] = 'Could not follow the user.';
    }
    // unfollow user
} else if (($_REQUEST['action']) == 'unfollow') {

    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['friend_id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information.';
        echo json_encode($return);
        return;
    }

    $user_id = htmlentities($_REQUEST['user_id']);
    $follow_id = htmlentities($_REQUEST['friend_id']);

    $result = $access->deleteFollow($user_id, $follow_id);

    // if we got something
    if ($result) {

        $return['status'] = '200';
        $return['message'] = 'Stop following successfully.';

        // nothing has been gotten
    } else {

        $return['status'] = '400';
        $return['message'] = 'Could not stop following the user.';
    }
}


echo json_encode($return);
$access->disconnect();
