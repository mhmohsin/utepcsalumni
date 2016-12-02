<?php

require_once 'dbconnect.php';
require_once 'Users.php';
require_once 'template.php';

echo $header;
echo $navLink;


$profileId = 0;
$urlId = 0;

if (isset($_GET['id'])) {
    $urlId = $_GET['id'];
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT id FROM RegisteredUser WHERE username='$username'";
    $result = $teamconnection->query($query);
    $row = $result->fetch_assoc();
    $profileId = $row['id'];
}

if (!isset($_SESSION['username']) || $urlId == 0) {
    echo "<div id='error'>ERROR: YOU DON'T HAVE ACCESS TO THIS PAGE.<br>"
    . "Go <a href='index.php'>Back</a>.<br>";
    exit();
}

if ($urlId != $profileId) {
    echo "ERROR: YOU'RE NOT AUTHORIZED TO MODIFY OTHERS INFORMATION. <br>";
    exit();
}

function get_privacy_settings($profileId) {
    $privacy_settings = array();
    global $teamconnection;

    $query = "SELECT * FROM RegisteredUser WHERE id=$profileId";
    $result = $teamconnection->query($query);
    if (!$result) {
        die($teamconnection->error);
    }
    $row = $result->fetch_assoc();
    $privacy_settings['isdob'] = $row['isdob'] ? "" : "checked";
    $privacy_settings['isgender'] = $row['isgender'] ? "" : "checked";
    $privacy_settings['isaddress'] = $row['isaddress'] ? "" : "checked";
    $privacy_settings['isphone'] = $row['isphone'] ? "" : "checked";
    $privacy_settings['isemail'] = $row['isemail'] ? "" : "checked";
    return $privacy_settings;
}

// Database updater
function set_access_modifer($ID, $dob, $gender, $address, $phone, $email) {
    global $teamconnection;
    $query = "UPDATE RegisteredUser SET "
            . "isdob = '$dob', "
            . "isgender = '$gender', "
            . "isaddress = '$address', "
            . "isphone = '$phone', "
            . "isemail = '$email' "
            . "where id = '$ID'";
    $result = $teamconnection->query($query);
    if (!$result) {
        die($teamconnection->error);
        return FALSE;
    } else {
        return TRUE;
    }
}

// Processing the form
if (isset($_POST['submit'])) {
    if (isset($_POST['privacy'])) {
        $privacy = $_POST['privacy'];
        $isdob = (isset($privacy[0])) ? 0 : 1;
        $isgender = (isset($privacy[1])) ? 0 : 1;
        $isaddress = (isset($privacy[2])) ? 0 : 1;
        $isphone = (isset($privacy[3])) ? 0 : 1;
        $isemail = (isset($privacy[4])) ? 0 : 1;
        if (set_access_modifer($profileId, $isdob, $isgender, $isaddress, $isphone, $isemail)) {
            echo "Update successful.<br>";
        } else {
            "ERROR: PRIVACY SETTINGS COULDN'T BE UPDATED. <br>";
        }
    }
}

// Retriving the personal info from the team database
$query = "SELECT * FROM RegisteredUser WHERE id=$profileId";
$result = $teamconnection->query($query);
if (!$result) {
    die($teamconnection->error);
}
$row = $result->fetch_assoc();
$privacy_settings = get_privacy_settings($profileId);
echo "<h2>Change Privacy Settings</h2>";
echo "<form action = '" . $_SERVER['PHP_SELF'] . "?id=$profileId' method='POST'>";
echo "<section id='elements' class='fix'>"
 . "<p class='elements'>"
 . "<span>Date of birth </span>Private"
 . " <input type='checkbox' name='privacy[0]' value='0'" . $privacy_settings['isdob'] . "> <span>" . $row['dob'] . "</span>"
 . "</p>"
 . "<p class='elements'>"
 . "<span>Gender </span>Private"
 . "<input type='checkbox' name='privacy[1]' value='0'" . $privacy_settings['isgender'] . "><span>" . $row['gender'] . "</span>"
 . "</p>"
 . "<p class='elements'>"
 . "<span>Address </span>Private"
 . " <input type='checkbox' name='privacy[2]' value='0" . $privacy_settings['isaddress'] . "'><span>" . $row['address'] . "</span>"
 . "</p>"
 . "<p class='elements'>"
 . "<span>Phone </span>Private"
 . " <input type='checkbox' name='privacy[3]' value='0'" . $privacy_settings['isphone'] . "><span>" . $row['phone'] . "</span>"
 . "</p>"
 . "<p class='elements'>"
 . "<span>Email </span>Private"
 . " <input type='checkbox' name='privacy[4]' value='0'" . $privacy_settings['isemail'] . "><span>" . $row['email'] . "</span>"
 . "</p>"
 . "<input type='submit' name='submit' value='Submit'></form>"
 . "</section>";

echo $footer;
?>