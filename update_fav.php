<?php
session_start();
require('connect.php');

$f = $_POST['fav'];

$sql = 'SELECT movieId FROM movies WHERE title="'.$f.'" OR movieId="'.$f.'"';
$movieId = trim(mysqli_fetch_assoc(mysqli_query($connection, $sql))["movieId"]);

$sql = "INSERT IGNORE INTO ratings (username,movieId,rating) VALUES ('".$username."',".$movieId.",TRUE)";
if ($connection->query($sql) === TRUE) {
    $res="Successfully inserted new favorite(s).";
    echo json_encode($res);
} else {
    $error="Database error! ".$sql;
    echo json_encode($error);
}

mysqli_close($connection);

?>