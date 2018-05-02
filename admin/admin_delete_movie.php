<?php
session_start();
require('../connect.php');

if($username != "admin"){
    header("location: ../mymovies.php");
} else {
    $id = $_GET['id'];
    $sql = "DELETE FROM movies WHERE movieId = ".$id;

    if ($connection->query($sql) === TRUE) {
        echo "<script>
                alert('Movie deleted from database.');
                window.location.href='admin_movies.php'</script>";

    } else {
        echo "<script>
                alert('Movie deleted from database.');
                window.location.href='admin_movies.php'</script>";
    }
}