<?php

require_once 'Users.php';
require_once 'dbconnect.php';
require_once 'template.php';

echo $header;
echo $navLink;
echo "<h2>Alumni Database</h2>";

$results_per_page = 50; // number of results per page
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM " . $datatable . " ORDER BY Lastname ASC LIMIT $start_from, " . $results_per_page;
//$sql = "SELECT * FROM " . $datatable . " LIMIT $start_from, " . $results_per_page;
//        $sql = "SELECT * FROM " . $datatable . " WHERE AcademicYear='2004-05' LIMIT " . $start_from . ", " . $results_per_page;
//        $sql = "SELECT * FROM $datatable WHERE AcademicYear='2004-05' LIMIT $start_from, $results_per_page";
$rs_result = $connection->query($sql);

echo "<section id='elements' class='fix'>";
//. "<p class='elements'><span></span>Academic Year</p>";
while ($row = $rs_result->fetch_assoc()) {
    echo "<p class='elements'><span><a href='viewprofile.php?id="
    . $row["id"] . "'>" . $row["LastName"] . " " . $row["FirstName"] . "</span></a>" . $row['AcademicYear'] . "</p>";
}
echo "</section>";
echo "Pages: ";
$sql = "SELECT COUNT(id) AS total FROM " . $datatable;
$result = $connection->query($sql);
$row = $result->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page); // calculate total pages with results

for ($i = 1; $i <= $total_pages; $i++) {  // print links for all pages
    echo "<a href='index.php?page=" . $i . "'";
    if ($i == $page) {
//        echo " class='curPage'";
    }
    echo ">" . $i . "</a> ";
}

echo $footer;
?>