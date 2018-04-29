<?php

session_start();
$username = $_SESSION['username'];
$connection = mysqli_connect('localhost', 'root', 'x8C3wEqZd4DdoxwP', 'db_recomm');

if (!$connection){
    die("Database Connection Failed" . mysqli_error($connection));
}
