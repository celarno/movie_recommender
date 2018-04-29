<?php
session_start();
require('console_log.php');
require('api_keys.php');
require('connect.php');

$sql = "SELECT * FROM db_user.user WHERE username='".$username."'";
$result = mysqli_query($connection, $sql);
$data = mysqli_fetch_assoc($result);
mysqli_close($connection);


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Movies</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>

    <link rel="stylesheet" type="text/css" href="style_main.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <div class="navbar-header">
                <span class="navbar-brand"><a href="#"><i class="fas fa-tv"></i></a></span>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="mymovies.php">My Movies <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recommendations.php">Recommendations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="browse.php">Browse Movies</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><span class="nav-link"><i class="fas fa-user-circle"></i> Welcome, <?php echo $username?></span></li>
                    <li class="nav-item"><a class="nav-link" href='logout.php'><i class="fas fa-external-link-alt"></i> Logout</button></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="main">
        <h2>My Profile</h2>
            <?php

            echo '<p><b>Username: </b>'. $data["username"] .'</p>';
            echo '<p><b>Password: </b>'. $data["password"] .'</p>';
            echo '<p><b>E-Mail:   </b>'. $data["email"] .'</p>';


            ?>
    </div>
</div>
</body>
</html>