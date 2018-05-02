<?php

session_start();
$username = $_SESSION['username'];

$connection = mysqli_connect('85.10.205.173:3306', 'root_root', 'x8C3wEqZd4DdoxwP', 'db_recomm');

if (!$connection){
    die("Database Connection Failed" . mysqli_error($connection));
}

?>