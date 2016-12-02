<?php

// Log out page, logs out the user and destroys the session.

require_once 'Users.php';
require_once 'dbconnect.php';

if (isset($_SESSION['username'])) {
    $user = new Users($_SESSION['username'], $connection);
    $user->destroy_session();
}
header("Location:index.php");
?>