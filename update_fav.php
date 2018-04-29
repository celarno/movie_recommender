<?php
session_start();
$username = $_SESSION['username'];
$f = $_POST['fav'];

$connection = mysqli_connect('localhost', 'root', 'x8C3wEqZd4DdoxwP');

$sql = 'SELECT movieId FROM db_recomm.movies WHERE title="'.$f.'" OR movieId="'.$f.'"';
$movieId = trim(mysqli_fetch_assoc(mysqli_query($connection, $sql))["movieId"]);

$sql = "INSERT IGNORE INTO db_user.ratings (username,movieId,rating) VALUES ('".$username."',".$movieId.",TRUE)";
if ($connection->query($sql) === TRUE) {
    $res="Successfully inserted new favorite(s).";
    echo json_encode($res);
} else {
    $error="Database error! ".$sql;
    echo json_encode($error);
}

mysqli_close($connection);

?>