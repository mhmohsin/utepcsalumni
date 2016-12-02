<?php

require_once 'dbconnect.php';
require_once 'Users.php';
require_once 'template.php';

echo $header;
echo $navLink;

$profileId = 0;

// Getting the profile ID
if (isset($_GET['id'])) {
    $profileId = $_GET['id'];
} else if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT id FROM RegisteredUser WHERE username='$username'";
    $result = $teamconnection->query($query);
    $row = $result->fetch_assoc();
    $profileId = $row['id'];
} else {
    echo "ERROR: PROFILE ID NOT FOUND. <br>";
    exit();
}

function get_term($input) {
    $year = intval(substr($input, 0, 4));
    $semester = intval(substr($input, 4, 2));
    switch ($semester):
        case 10:
            $semester = "Fall";
            $year = $year - 1;
            break;
        case 20:
            $semester = "Spring";
            break;
        case 30:
            $semester = "Summer";
            break;
        default :
    endswitch;
    return $semester . " " . $year;
}

// Retrieving academic info from the main database
$sql = "SELECT * FROM $datatable WHERE id='$profileId'";
$rs_result = $connection->query($sql);
$row = $rs_result->fetch_assoc();

echo "<h1>" . $row['FirstName'] . " " . $row['LastName'] . "</h1>"
 . "<div id='userphoto'><img src='images/avatar.png' alt='default avatar'></div>";


echo "<h2>Academic Info</h2>";
echo "<section id='elements' class='fix'>"
 . "<p class='elements'><span>Academic year </span>" . $row['AcademicYear'] . "</p>"
 . "<p class='elements'><span>Term </span>" . get_term($row['Term']) . "</p>"
 . "<p class='elements'><span>Major </span>" . $row['Major'] . "</p>"
 . "<p class='elements'><span>Level </span>" . $row['LevelCode'] . "</p>"
 . "<p class='elements'><span>Degree </span>" . $row['Degree'] . "</p>"
 . "</section>";

// Retriving the personal info from the team database
$query = "SELECT * FROM RegisteredUser WHERE id=$profileId";
$result = $teamconnection->query($query);
if (!$result) {
    die($teamconnection->error);
}
$row = $result->fetch_assoc();

if ($result->num_rows != 0) {
    $edit = "";
    if (isset($_SESSION['username'])) {
        $query = "SELECT username FROM RegisteredUser WHERE id=$profileId";
        $rslt = $teamconnection->query($query);
        $r = $rslt->fetch_assoc();
        if ($r['username'] === $_SESSION['username']) {
            $edit = "<a href='update.php?id=$profileId'><img src='images/edit.png' alt='Edit'></a>";
        }
    }

    echo "<h2>Personal Info $edit</h2>";
    echo "<section id='elements' class='fix'>";
    $isLoggedIn = isset($_SESSION['username']) ? 1 : 0;

    if ($row['isdob'] || $isLoggedIn) {
        echo "<p class='elements'><span>Date of birth </span>" . $row['dob'] . "</p>";
    }
    if ($row['isgender'] || $isLoggedIn) {
        echo "<p class='elements'><span>Gender </span>" . $row['gender'] . "</p>";
    }
    if ($row['isaddress'] || $isLoggedIn) {
        echo "<p class='elements'><span>Address </span>" . $row['address'] . "</p>";
    }
    if ($row['isphone'] || $isLoggedIn) {
        echo "<p class='elements'><span>Phone </span>" . $row['phone'] . "</p>";
    }
    if ($row['isemail'] || $isLoggedIn) {
        echo "<p class='elements'><span>Email </span>" . $row['email'] . "</p>";
    }
    echo "</section>";
} else {
    echo "Personal info is not available at this time. <br>";
}

$result->close();
$teamconnection->close();

echo $footer;
?>