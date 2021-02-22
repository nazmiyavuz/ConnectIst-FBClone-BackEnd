<?php


// Uploading Files (cover / ava) to the server and updating the path in database

// STEP 1. Receive passed data to current PHP file

if (empty($_REQUEST['id']) || empty($_REQUEST['type'])) {

    $return['status'] = '400';
    $return['message'] = 'Missing required information';
    echo json_encode($return);
}

// using protection method to cast received data 
$id = htmlentities($_REQUEST['id']);
$type = htmlentities($_REQUEST['type']);
$return = array();

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

// STEP 3. Upload file
if (isset($_FILES['file']) && $_FILES['file']['size'] > 1) {

    // Applications > XAMPP > xamppfiles > htdocs > fb > cover/ava > 777
    $folder = '/Applications/XAMPP/xamppfiles/htdocs/connectIst/' . $type . '/' . $id;

    // server is automatically creating dedicated folder for every user to store his files
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    // Applications > XAMPP > xamppfiles > htdocs > fb > cover/ava > 777 > ava.jpg
    $filePath = $folder . '/' . basename($_FILES['file']['name']);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {

        // http://localhost/fb/cover(ava)/777/ava.jpg
        $fileURL = 'http://localhost/connectIst/' . $type . '/' . $id . '/' . $_FILES['file']['name'];

        // update the URL path in the server
        $access->updateImageURL($type, $fileURL, $id);

        // throwing back UPDATED infromation related to the user (e.g. cover, ava)
        $user = $access->selectUserID($id);

        // return JSON to the user
        $return['status'] = '200';
        $return['message'] = 'File ' . $type . ' has been uploaded successfully';
        $return['' . $type . ''] = $fileURL;
        $return['id'] = $user['id'];
        $return['email'] = $user['email'];
        $return['userName'] = $user['userName'];
        $return['fullName'] = $user['fullName'];
        $return['cover'] = $user['cover'];
        $return['ava'] = $user['ava'];
        $return['bio'] = $user['bio'];
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not upload the file';
    }
}


$access->disconnect();
echo json_encode($return);
