<?php

require_once 'dbconnect.php';
require_once 'Users.php';
require_once 'template.php';

echo $header;
echo $navLink;

function add_message($connection, $id, $lastname, $message) { ///Isertt message
    $query = "INSERT INTO Messages VALUES('$id','$lastname', '$message',NOW())";
    $result = $connection->query($query);
    if (!$result) {
        die($connection->error);
    }
}

if (isset($_SESSION['username'])) {
    add_message($teamconnection, 100, 'Mohsin', "HOLA");
    $query = "SELECT * FROM Messages ORDER BY timeDate DESC";

    $result = $teamconnection->query($query);

    if (!$result)
        die($teamconnection->error);

    $rows = $result->num_rows;

    for ($j = 0; $j < $rows; ++$j) {
        $row = mysqli_fetch_row($result);
        $padding = str_repeat("&nbsp", 50);
        echo "Sender : " . $row[1] . $padding . $row[3] . "<br>";
        echo "Message:" . "<br>";
        echo $row[2] . "<br><br><br>";
    }

    $result->close();
    $teamconnection->close();
} else {
    header("Location:index.php");
    exit();
}



echo $footer;
?>