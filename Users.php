<?php

// Users class

session_start();

class Users {

    private $username = "";
    private $token = "";
    private $salt1 = "";
    private $salt2 = "";
    private $table = "";
    private $connection = "";

    public function __construct($username, $connection, $datatable) {
        $this->username = $this->fix_string($username);
        $this->token = $this->setToken("");
        $this->salt1 = "3KBIY*G(^*&";
        $this->salt2 = $this->username;
        $this->connection = $connection;
        $this->table = $datatable;
    }

// Getting the username
    public function getUsername() {
        return $this->username;
    }

// Adds user to the database
    public function addUser($id, $firstname, $lastname, $username, $password, $dob, $gender, $address, $phone, $email) {
        if ($this->isUserExist()) {
            return "User already exists!."
                    . " Please <a href='login.php'>login.</a> <br>";
        }
        $fail = "";
        $fail .= $this->validate_name($firstname);
        $fail .= $this->validate_name($lastname);
        $fail .= $this->validate_username($username);
        $fail .= $this->validate_password($password);
        $fail .= $this->validate_dob($dob);
        $fail .= $this->validate_phone($phone);
        $fail .= $this->validate_email($email);
//        $fail .= $this->validate_accesstype($accesstype);

        if ($fail === "") {
            $pass = $this->setToken($password);
            // Privacey settings: Private: 0; Public: 1
            // For now, everything is public. User can change that later.
            $query = "INSERT INTO $this->table "
                    . "VALUES('$id', '$username', '$pass','$dob', '1', '$gender', '1', '$address', '1', '$phone', '1', '$email', '1')";
            if ($this->connection->query($query) === TRUE) {
                return "";
            }
        } else {
            return $fail;
        }
    }

// Verifies if the user already in the database.
    public function isUserExist() {
        $username = $this->getUsername();
        $query = "SELECT * FROM $this->table WHERE username='$username'";
        $result = $this->connection->query($query);
        if (!$result) {
            die($this->connection->error);
        }
        $rows = $result->num_rows;
        if ($rows) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

// Validates the username input
    private function validate_username($field) {
        if ($field === "") {
            return "No Username was entered<br>";
        } else if (strlen($field) < 5) {
            return "Usernames must be at least 5 characters<br>";
        } else if (preg_match("/[^a-zA-Z0-9_-]/", $field)) {
            return "Only letters, numbers, - and _ in usernames<br>";
        }
        return "";
    }

// Validates the password input
    private function validate_password($field) {
        if ($field === "") {
            return "No Password was entered<br>";
        } else if (strlen($field) < 6) {
            return "Passwords must be at least 6 characters.<br>";
        } else if (!preg_match("/[a-z]/", $field) ||
                !preg_match("/[A-Z]/", $field) ||
                !preg_match("/[0-9]/", $field)) {
            return "Passwords require 1 each of a-z, A-Z and 0-9.<br>";
        }
        return "";
    }

// Validates and checks the availability of the firstname in the database
    private function validate_name($field) {
        if ($field === "") {
            return "No name entered<br>";
        } else if (preg_match("/[^a-zA-Z0-9]/", $field)) {
            return "Only letters are allowed in the name field.<br>";
        }
        return "";
    }

// Validates the date of birth
    private function validate_dob($field) {
        if ($field === "") {
            return "No date of birth entered. <br>";
        } else if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $field)) {
            return "Invalid date entered. <br>";
        }
        return "";
    }

// Validates the phone number
    private function validate_phone($field) {
        if ($field === "") {
            return "No phone number entered. <br>";
        } else if (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $field)) {
            return "Invalid phone/format entered. <br>";
        }
        return "";
    }

    // Validates the email address
    private function validate_email($field) {
        if ($field == "") {
            return "No Email was entered. <br>";
        } else if (!((strpos($field, ".") > 0) && (strpos($field, "@") > 0)) || preg_match("/[^a-zA-Z0-9.@_-]/", $field)) {
            return "The Email address is invalid. <br>";
        }
        return "";
    }

//    // Validates the accesstype
//    private function validate_accesstype($field) {
//        if ($field === "private" || $field === "public") {
//            return "";
//        } else {
//            return "Invalid profile privacy settings. <br>";
//        }
//    }
//    // Retrieves the user id from the main database
//    private function get_id($firstname, $lastname) {
//        require_once 'dbconnect.php';
//        $query = "SELECT id FROM csdegrees where FirstName='$firstname' AND LastName = '$lastname' ";
//        $result = $connection->query($query);
//        if (!$result)
//            die($connection->error);
//        if (!$result)
//            die($connection->error);
//        $row = mysqli_fetch_row($result);
//        return $row[0];
//    }
// Sanitizes user input
    private function fix_string($string) {
        if (get_magic_quotes_gpc()) {
            $string = stripslashes($string);
        }
        return htmlentities($string);
    }

//    // Generates the links depending on the usertype
//    public function getLinks() {
//        $mainpage = "<a href='index.php'>Home Page</a>";
//        $registrationpage = "<a href='registration.php'>Registration</a>";
//        $profilepage = "<a href='viewprofile.php'>My profile</a>";
//        $signout = "<a href='logout.php'>Sign out</a>";
//        $signin = "<a href='login.php'>Sign in</a>";
//
//        if (isset($_SESSION['username'])) {
//            return $mainpage . " | " . $profilepage . " | " . $signout . "<hr>";
//        } else {
//            return $mainpage . " | " . $registrationpage . " | " . $signin . "<hr>";
//        }
//        switch ($this->getUserType()):
//            case 2:
//                return $mainpage . " | " . $userpage . " | " . $registrationpage . " | " . $signout . "<hr>";
//            case 3:
//                return $mainpage . " | " . $userpage . " | " . $adminpage . " | " . $registrationpage . " | " . $signout . "<hr>";
//            default:
//                return $mainpage . " | " . $registrationpage . " | " . $signin . "<hr>";
//        endswitch;
//    }
// Generates token using ripemd hashing algorithms along with the salts.
    private function setToken($password) {
        return hash("ripemd128", $this->salt1 . $password . $this->salt2);
    }

// Returns the user type
//    private function getUserType() {
//        if (!isset($_SESSION['username'])) {
//            return 1;
//        }
//        if (isset($_SESSION['username'])) {
//            $username = $_SESSION['username'];
//        }
//        $query = "SELECT * FROM $this->table WHERE username='$username'";
//        $result = $this->connection->query($query);
//        $row = $result->fetch_array(MYSQLI_NUM);
//        if ($row) {
//            return $row[3];
//        } else {
//            return 1;
//        }
//    }
// Verifies the username and the password using the database.
    public function verifyUser($password) {
        $username = $this->getUsername();
        $query = "SELECT * FROM $this->table WHERE username='$username'";
        $result = $this->connection->query($query);
        if (!$result) {
            die("Couldn't connect to the database.");
        }
        $row = $result->fetch_array(MYSQLI_NUM);
        if ($row) {
            if ($this->setToken($password) === $row[2]) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

// Destroys the session.
    public function destroy_session() {
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
    }

}

?>