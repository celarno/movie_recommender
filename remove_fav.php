<?php
session_start();
$username = $_SESSION['username'];
$f = $_POST['fav'];

$connection = mysqli_connect('localhost', 'root', 'x8C3wEqZd4DdoxwP','db_user');
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