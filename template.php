<?php

$header = "<!DOCTYPE html>"
        . "<html>"
        . "<head>"
        . "<meta charset='UTF-8'>"
        . "<title>CS Alumni - The University of Texas at El Paso</title>"
        . "<link rel='stylesheet' type='text/css' href='style.css'>"
        . "<script type='text/javascript' src='js/jquery-1.10.2.min.js'></script>"
        . "<script src='script.js'></script>"
        . "</head>"
        . "<body>"
        . "<div id='w'>"
        . "<div id='content' class='clearfix'>"
        . "<h1>UTEP Computer Science</h1>";

$navLink = getLinks();

$footer = "</div>"
        . "</div>"
        . "</body>"
        . "</html>";

// Generates the links depending on the usertype

function getLinks() {
    $links = "<nav id='menutabs'>"
            . "<ul class='clearfix'>";
    $mainpage = "<li><a href='index.php'>Home Page</a></li>";
    $registrationpage = "<li><a href='registration.php'>Registration</a></li>";
    $profilepage = "<li><a href='viewprofile.php'>My profile</a></li>";
    $signout = "<li><a href='logout.php'>Sign out</a></li>";
    $signin = "<li><a href='login.php'>Sign in</a></li>";

    if (isset($_SESSION['username'])) {
//        return $mainpage . " | " . $profilepage . " | " . $signout . "<hr>";
        $links .= $mainpage . $profilepage . $signout
                . "</ul>"
                . "</nav>";
        return $links;
    } else {
//        return $mainpage . " | " . $registrationpage . " | " . $signin . "<hr>";
        $links .= $mainpage . $registrationpage . $signin
                . "</ul>"
                . "</nav>";
        return $links;
    }
}

?>