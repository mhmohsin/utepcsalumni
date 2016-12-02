<?php

require_once 'dbconnect.php';
require_once 'Users.php';
require_once 'template.php';

echo $header;
echo $navLink;

if (isset($_SESSION['username'])) {
    echo "You're already registered!.<br>"
    . "Please logout if you want to register another account. <br>";
} else if (isset($_POST['register'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    // Getting user id from the main database
    $query = "SELECT id FROM csdegrees where FirstName='$firstname' AND LastName = '$lastname' ";
    $res = $connection->query($query);
    if (!$res) {
        die($connection->error);
    }
    $row = $res->fetch_assoc();
    $id = $row['id'];

    $username = $_POST['username'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
//    $accesstype = $_POST['accesstype'];
    $user = new Users($_POST['username'], $teamconnection, $teamdatatable);
    $result = $user->addUser($id, $firstname, $lastname, $username, $password, $dob, $gender, $address, $phone, $email);
    if ($result === "") {
        echo "Congratulations " . $user->getUsername() . "! You're registered." . "<br>"
        . "Please login <a href='login.php'>here.</a>.<br>";
    } else {
        echo "ERROR: <br>" . $result;
    }
} else {
    echo "<div>"
    . "<form action = 'registration.php' method='POST' onSubmit='return validate(this)'>"
    . "<label>First Name: </label>"
    . "<input type ='text' name='firstname' autofocus='autofocus'><br>"
    . "<label>Last Name: </label>"
    . "<input type ='text' name='lastname'><br>"
    . "<label>Username: </label>"
    . "<input type ='text' name='username'><br>"
    . "<label>Password: </label>"
    . "<input type='password' name='password'><br>"
    . "<label>Date of birth: </label>"
    . "<input type='text' name='dob'> Format: yyyy-mm-dd<br>"
    . "<label>Gender: </label>"
    . "<input type='radio' name='gender' value='male' checked> Male"
    . "<input type='radio' name='gender' value='female'> Female<br>"
    . "<label>Address: </label>"
    . "<textarea name='address' rows='4' cols='50'>"
    . "</textarea><br>"
    . "<label>Phone Number: </label>"
    . "<input type='text' name='phone' > Format: XXX-XXX-XXXX<br>"
    . "<label>Email: </label>"
    . "<input type='text' name='email'><br>"
    . "<input type='submit' name='register' value='Register'>"
    . "<input type='reset'><br>"
    . "<small><i>All personal information will be public."
    . " Please change the privacy settings after login to your profile page.</i></small><br>"
    . "</form>"
    . "</div>";
}
echo $footer;
?>