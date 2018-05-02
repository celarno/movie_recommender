<?php
session_start();
require('connect.php');

$username = $_SESSION['username'];
$email = $_POST['e'];
$password = $_POST['p'];

$query = 'UPDATE users SET email="'. $email. '", password="'.$password.'",
                active=1, admin=0 WHERE username="'.$username.'"';
$result = mysqli_query($connection, $query);
if($result){
    $msg = "<div class='alert alert-success' role='alert'>Profile data successfully updated.</div>";
} else {
    $msg = "<div class='alert alert-danger' role='alert'>Profile data could not be updated.</div>";
}

echo json_encode($msg);
