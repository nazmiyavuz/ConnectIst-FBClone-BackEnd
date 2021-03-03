<?php

// This file is responsible for uploading information into server.

// STEP 1. Received passed information. If some of the values are emty -> return and do not executed the code further.
if (empty($_REQUEST['user_id']) || empty($_REQUEST['text'])) {
    $return['status'] = '400';
    $return['message'] = 'Missing required information';
    echo json_encode($return);
    return;
}

// safe method of casting received values.
$user_id = htmlentities($_REQUEST['user_id']);
$text = htmlentities($_REQUEST['text']);
$picture = '';

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

// STEP 3. Check, is image selected / passed or not and check the size of the image to be uploaded (must be +1 kb)
if (isset($_FILES['file']) && $_FILES['file']['size'] > 1) {

    // declaring absolute path to the folder for every user with his unique id
    $folder = '/Applications/XAMPP/xamppfiles/htdocs/connectIst/posts/' . $user_id; // '/Applications/XAMPP/xamppfiles/htdocs/connectIst/posts/22'

    // creating folder if it doesn't exist
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    } else {
        $return['folder_message'] = 'Could not create a directory';
    }

    // path to the file. Full path of the file.
    $path = $folder . '/' . basename($_FILES['file']['name']); // '/Applications/XAMPP/xamppfiles/htdocs/connectIst/posts/22/picture1.jpg'

    // if file is uploaded succesfully, then -> ... else/or ... ->
    if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
        // creating URL link to the image uploaded
        $picture = 'http://localhost/connectIst/posts/' . $user_id . '/' . $_FILES['file']['name']; // http://localhost/connectIst/posts/22//picture1.jpg
        $return['picture'] = $picture;
    } else {
        $return['file_message'] = 'Could not upload the picture';
    }
}

// STEP 4. Insert details into database
$result = $access->insertPost($user_id, $text, $picture);

if ($result) {
    $return['status'] = '200';
    $return['message'] = 'Post uploaded successfully';
} else {

    $return['status'] = '400';
    $return['message'] = ' Could not upload post';
}

// disconnect and throw back JSON to the user
$access->disconnect();
echo json_encode($return);
