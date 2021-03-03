<?php


// Declare class to access this php file
class access
{

    // connection global variables
    var $host = null;
    var $user = null;
    var $pass = null;
    var $name = null;
    var $conn = null;
    var $result = null;

    // constructing class
    function __construct($dbhost, $dbuser, $dbpass, $dbname)
    {
        $this->host = $dbhost;
        $this->user = $dbuser;
        $this->pass = $dbpass;
        $this->name = $dbname;

        // establish connection
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);

        // error occured while constructing the class
        if (mysqli_connect_errno()) {
            echo 'Could not construct';
            return;
        }

        $this->conn->set_charset('utf8');
    }


    // establish connection with the server
    public function connect()
    {

        // establishing connection
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);

        // error occured while connecting
        if (mysqli_connect_errno()) {
            echo 'Could not connect';
            return;
        }

        $this->conn->set_charset('utf8');
    }


    // disconnect from the server once we finished using the server connection
    public function disconnect()
    {

        // if the connection is not null (established), close it
        if ($this->conn != null) {
            $this->conn->close();
        }
    }

    // Will try to select any value in database based on received Email
    public function selectUserWithEmail($email)
    {

        // array to store full user related information with the logic: Key=>Value (Name=>John)
        $returnArray = array();

        // SQL Language / Commande to be sent to the server
        // SELECT * FROM users WHERE email='john@yahoo.com'
        $sql = "SELECT * FROM users WHERE email='" . $email . "'";

        // execuring query via already established connection with the server
        $result = $this->conn->query($sql);

        // result isn't zero and it has at least 1 row / value / result
        if ($result != null && (mysqli_num_rows($result)) >= 1) {

            // converting to be a JSON type
            $row = $result->fetch_array(MYSQLI_ASSOC);

            // assign fetched row to ReturnArray
            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        // throw back returnArray
        return $returnArray;
    }

    // Will try to select any value in database based on received Email
    public function selectUserWithUserName($userName)
    {

        // array to store full user related information with the logic: Key=>Value (Name=>John)
        $returnArray = array();

        // SQL Language / Commande to be sent to the server
        // SELECT * FROM users WHERE userName='nazmi'
        $sql = "SELECT * FROM users WHERE userName='" . $userName . "'";

        // execuring query via already established connection with the server
        $result = $this->conn->query($sql);

        // result isn't zero and it has at least 1 row / value / result
        if ($result != null && (mysqli_num_rows($result)) >= 1) {

            // converting to be a JSON type
            $row = $result->fetch_array(MYSQLI_ASSOC);

            // assign fetched row to ReturnArray
            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        // throw back returnArray
        return $returnArray;
    }

    // Inserting Data in the server receiving from the user (e.g. register.php)
    public function registertUser($email, $secured_password, $salt, $userName, $fullName)
    {
        // SQL Language - command to inser data   
        $sql = "INSERT INTO users SET email=?, password=?, salt=?, userName=?, fullName=?";

        // preparing SQL for execution by checking the validity
        $statement = $this->conn->prepare($sql);

        // if error
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // assigning a variables instead of '?', after checking the preparation and validty of the SQL command
        $statement->bind_param('sssss', $email, $secured_password, $salt, $userName, $fullName);

        // $result will store the status / result of the execution of SQL command
        $result = $statement->execute();

        return $result;
    }

    // updating the path of the image (stored in the server) in the database
    function updateImageURL($type, $path, $id)
    {

        // UPDATE users SET ava=? WHERE id=?
        // $sql = 'UPDATE users SET ' . $type . ' = ? WHERE users.id = ?';
        $sql = 'UPDATE users SET ' . $type . '=? WHERE id=?';

        // prepare command to be executed
        $statement = $this->conn->prepare($sql);

        // if error occured while execution
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // assigning parameters to the prepared command execution
        $statement->bind_param('si', $path, $id);

        // $result will store the result of executed statement
        $result = $statement->execute();

        return $result;
    }

    // Will try to select any value in database based on received Email
    public function loginUser($email)
    {
        // array to store full user related information with the logic: Key=>Value (Name=>John)
        $returnArray = array();

        // SQL Language / Commande to be sent to the server
        // SELECT * FROM users WHERE email='john@yahoo.com'
        $sql = "SELECT * FROM users WHERE email='" . $email . "'";

        // execuring query via already established connection with the server
        $result = $this->conn->query($sql);

        // result isn't zero and it has at least 1 row / value / result
        if ($result != null && (mysqli_num_rows($result)) >= 1) {

            // converting to be a JSON type
            $row = $result->fetch_array(MYSQLI_ASSOC);

            // assign fetched row to ReturnArray
            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        // throw back returnArray
        return $returnArray;
    }

    // Will try to select any value in database based on received Email
    public function selectUserID($id)
    {

        // array to store full user related information with the logic: Key=>Value (Name=>John)
        $returnArray = array();

        // SQL Language / Commande to be sent to the server
        // SELECT * FROM users WHERE id='777'
        $sql = "SELECT * FROM users WHERE id='" . $id . "'";

        // execuring query via already established connection with the server
        $result = $this->conn->query($sql);

        // result isn't zero and it has at least 1 row / value / result
        if ($result != null && (mysqli_num_rows($result)) >= 1) {

            // converting to be a JSON type
            $row = $result->fetch_array(MYSQLI_ASSOC);

            // assign fetched row to ReturnArray
            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        // throw back returnArray
        return $returnArray;
    }

    // update bio in the server
    function updateBio($id, $bio)
    {
        // declaring SQL Command
        $sql = 'UPDATE users SET bio=? WHERE id=?';

        // prepare SQL Command to be exec
        $statement = $this->conn->prepare($sql);

        // if error occourred while preparing the statement to be exec
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // assign params to be prepared SQL Command
        $statement->bind_param('si', $bio, $id);

        // access result of exec
        $result = $statement->execute();

        // returning result of exec
        return $result;
    }

    // inserting post into table/database
    function insertPost($user_id, $text, $picture)
    {

        // sql statement to be ran
        $sql = 'INSERT INTO posts SET user_id=?, text=?, picture=?';

        // preparing SQL command for execution
        $statement = $this->conn->prepare($sql);

        // show error if statement couldn't be executed.
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // replacing ? with the variables
        $statement->bind_param('iss', $user_id, $text, $picture);

        // execute statement and keep the result in $result variable
        $result = $statement->execute();

        return $result;
    }

    // responsible of updating user related information
    public function updateUser($email, $userName, $fullName, $id)
    {
        // sql statement to be ran
        $sql = 'UPDATE users SET email=?, userName=?, fullName=? WHERE id=?';

        // preparing SQL command for execution
        $statement = $this->conn->prepare($sql);

        // checking sql command
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // assigning values / replacing ? with the variables
        $statement->bind_param('sssi', $email, $userName, $fullName, $id);

        // execute statement and keep the result in $result variable
        $result = $statement->execute();

        // return the result of final execution.
        return $result;
    }

    // responsible of updating user password
    public function updatePassword($id, $password, $salt)
    {
        // sql statement to be ran
        $sql = 'UPDATE users SET password=?, salt=? WHERE id=?';

        // preparing SQL command for execution
        $statement = $this->conn->prepare($sql);

        // checking sql command
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // assigning values / replacing ? with the variables
        $statement->bind_param('ssi', $password, $salt, $id);

        // execute statement and keep the result in $result variable
        $result = $statement->execute();

        // return the result of final execution.
        return $result;
    }

    public function selectPosts($id, $offset, $limit)
    {
        // array to store information or posts
        $return = array();

        // sql statement to be executed
        $sql = "SELECT 
        posts.id, 
        posts.user_id, 
        posts.text, 
        posts.picture, 
        posts.date_created, 
        users.fullName, 
        users.cover, 
        users.ava, 
        likes.post_id AS liked 
        FROM posts 
        LEFT JOIN users ON users.id = posts.user_id 
        LEFT JOIN likes ON posts.id = likes.post_id 
        WHERE posts.user_id = $id 
        ORDER BY posts.date_created DESC LIMIT $limit OFFSET $offset";

        // preparing sql command to be executed and then we stor ethe result of preparation in statement var.
        $statement = $this->conn->prepare($sql);

        // show error occurred while preparing the sql command for execution
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // execute sql command
        $statement->execute();

        // retreive results from the query / sql
        $result = $statement->get_result();

        // all rows (posts) are stored in result. We are fetching every row one by one. and assigning it to $return var.
        while ($row = $result->fetch_assoc()) {
            $return[] = $row;
        }

        return $return;
    }

    // inserting like-information into the database
    public function insertLike($post_id, $user_id)
    {
        // sql statement to be ran
        $sql = 'INSERT INTO likes SET post_id=?, user_id=?';

        // preparing SQL command for execution
        $statement = $this->conn->prepare($sql);

        // checking is statement having an errors
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // assigning values / replacing ? with the variables
        $statement->bind_param('ii', $post_id, $user_id);

        // execute statement and keep the result in $result variable
        $result = $statement->execute();

        // return the result of final execution.
        return $result;
    }


    // inserting like-information into the database
    public function deleteLike($post_id, $user_id)
    {
        // sql statement to be ran
        $sql = 'DELETE FROM likes WHERE post_id=? AND user_id=?';

        // preparing SQL command for execution
        $statement = $this->conn->prepare($sql);

        // checking is statement having an errors
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // assigning values / replacing ? with the variables
        $statement->bind_param('ii', $post_id, $user_id);

        // execute statement and keep the result in $result variable
        $result = $statement->execute();

        // return the result of final execution.
        return $result;
    }

    // insert comment into db
    public function insertComment($post_id, $user_id, $comment)
    {

        // sql comment to be execuited 
        $sql = 'INSERT INTO comments SET post_id=?, user_id=?, comment=?';

        // preparing SQL command for execution
        $statement = $this->conn->prepare($sql);

        // checking is statement having an errors
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // assigning values / replacing ? with the variables
        $statement->bind_param('iis', $post_id, $user_id, $comment);

        // execute statement and keep the result in $result variable
        $result = $statement->execute();

        // return back to the user the result we got
        return $result;
    }

    // select all comments related to the certain post
    public function selectComments($post_id, $limit, $offset)
    {
        // array to store information or posts
        $return = array();

        // sql statement to be executed
        $sql = "SELECT 
                    comments.id, 
                    comments.post_id, 
                    comments.user_id, 
                    comments.comment, 
                    comments.date_created, 
                    posts.text, 
                    posts.picture, 
                    users.fullName, 
                    users.ava 
                    FROM comments 
                    LEFT JOIN posts ON posts.id = comments.post_id 
                    LEFT JOIN users ON users.id = comments.user_id 
                    WHERE post_id = $post_id 
                    ORDER BY comments.date_created DESC LIMIT $limit OFFSET $offset";

        // preparing sql command to be executed and then we stor ethe result of preparation in statement var.
        $statement = $this->conn->prepare($sql);

        // show error occurred while preparing the sql command for execution
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // execute sql command
        $statement->execute();

        // retreive results from the query / sql and asigning it to $result
        $result = $statement->get_result();

        // all rows (posts) are stored in result. We are fetching every row one by one. and assigning it to $return var.
        while ($row = $result->fetch_assoc()) {
            $return[] = $row;
        }

        return $return;
    }
}
