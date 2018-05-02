<?php

session_start();
$username = $_SESSION['username'];

//$connection = mysqli_connect('localhost', 'root', 'x8C3wEqZd4DdoxwP', 'db_recomm');
$connection = mysqli_connect('sql312.epizy.com', 'epiz_22019692', 'SN9-SFY-rxS-Ysk', 'epiz_22019692_db_recomm');

if (!$connection){
    die("Database Connection Failed" . mysqli_error($connection));
}

?>