<?php
session_start();
require('connect.php');

$username = $_SESSION['username'];
$f = $_POST['fav'];

$sql = "DELETE FROM ratings WHERE username = '".$username."' AND movieId = ".$f;

if ($connection->query($sql) === TRUE) {
    $res = "Movie ". $f ." removed.";
    echo json_encode($res);
} else {
    $error ="Database error! ".$sql;
    echo json_encode($error);
}

mysqli_close($connection);

?>