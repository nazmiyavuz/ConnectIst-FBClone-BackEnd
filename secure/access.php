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
}
