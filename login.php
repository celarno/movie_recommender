<?php
session_start();
require('connect.php');

if (isset($_POST['username']) and isset($_POST['password'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE username='$username' and password='$password'";
    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
    $count = mysqli_num_rows($result);

    if ($count == 1){
        $_SESSION['username'] = $username;
    } else {
        echo '<script language="javascript">';
        echo 'alert("Invalid Login Credentials")';
        echo '</script>';
    }
}
if (isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    header("location: mymovies.php");
} else {

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>

        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    </head>
    <body>

    <div class="container-fluid">
        <form class="form-signin" method="POST">
            <div class="welcome_header">
                <a href="index.php">
                <i class="fas fa-tv"></i>
                <h2>Movie Recommender</h2></a>
            </div>
            <br>
            <h3 class="form-signin-heading">Please Login</h3>
            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
            <a class="btn btn-lg btn-primary btn-block" href="register.php">Register</a>
        </form>
    </div>
    </body>
    </html>
<?php } ?>