<?php
session_start();
$username = $_SESSION['username'];

if($username != "admin"){
    header("location: ../mymovies.php");
}

require('../connect.php');
require('../user_picture.php');
$p = "../".$p;

if (!isset($_SESSION['username'])){
    header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - Movies</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/r-2.2.1/datatables.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" type="text/css" href="../style_main.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/r-2.2.1/datatables.min.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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
                    <a class="nav-link" href="../mymovies.php">My Movies <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../myprofile.php">My Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../recommendations.php">Recommendations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../browse.php">Browse</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">Admin</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><span class="nav-link"><img class="profile_pic" style="height:20px;width:20px;box-shadow:none;" src="<?php echo $p ?>">  Welcome, <?php echo $username?></span></li>
                <li class="nav-item"><a class="nav-link" href='../logout.php'><i class="fas fa-external-link-alt"></i> Logout</button></a></li>
            </ul>
        </div>
    </div>
</nav>
<div id="main">
    <h3>Admin Panel<span style="color:#979797;font-weight:200"> Movies</span></h3>
    <p>Manage table: <a href="admin_movies.php">Movies</a> | <a href="#" onclick="alert('Does not work yet ...');">Users</a></p>
    <p><button onclick="location.href='admin_add_movie.php';" type="button" class="btn btn-secondary">Add movie</button></p>
    <table id="movieTable" class="table table-hover table-sm table-responsive-lg">
        <thead>
        <tr>
            <th>movieId</th>
            <th>Title</th>
            <th>Year</th>
            <th>Genres</th>
            <th>Rating</th>
            <th>imdbId</th>
            <th>tmdbId</th>
            <th>Poster</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
</div>

<script>
    $(document).ready(function() {

        $('#movieTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "admin_movie_table.php"
        });

    });
</script>
</body>
</html>