<?php

require_once 'dbconnect.php';
require_once 'Users.php';
require_once 'template.php';

echo $header;
echo $navLink;

// Content
if (isset($_SESSION['username'])) {
    echo $_SESSION['username'] . ", you're already logged in.<br>";
    exit();
}
if (isset($_POST['login'])) {
    if ($_POST['username'] != "" && $_POST['password'] != "") {
        $user = new Users($_POST['username'], $teamconnection, $teamdatatable);
        if ($user->verifyUser($_POST['password'])) {
            $_SESSION['username'] = $user->getUsername();
//            echo "Welcome " . $user->getUsername();
            header("Location:viewprofile.php");
        } else {
            echo "Invalid username/password.<br>";
        }
    }
}
echo "<div id='form'>"
 . "<form action = '" . $_SERVER['PHP_SELF'] . "'method='POST'>"
 . "<label>Username: </label>"
 . "<input type ='text' name='username' autofocus='autofocus' placeholder='Enter your username'><br>"
 . "<label>Password: </label>"
 . "<input type='password' name='password' placeholder='Enter your password'><br>"
 . "<input type='submit' name='login' value='Login'><br>"
 . "</form>"
 . "If you want to register, please <a href='registration.php'>click here</a>.<br>"
 . "</div>";

echo $footer;
?>
