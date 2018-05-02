<?php
session_start();
$username = $_SESSION['username'];

if($username != "admin"){
    header("location: ../mymovies.php");
}

require('../connect.php');
require('../user_picture.php');
$p = "../".$p;

$title = $year = $genres = $rating = $tmdbId = $imdbId = $poster = "";

// processing form data when form is submitted
if(isset($_POST["title"]) && !empty($_POST["title"])){

    $title  = $_POST["title"];
    $year   = (int)$_POST["year"];
    $genres = $_POST["genres"];
    $rating = (float)$_POST["rating"];
    $tmdbId = (int)$_POST["tmdbId"];
    $imdbId = (int)$_POST["imdbId"];
    $poster = $_POST["poster"];

    $sql = "INSERT INTO movies (title,year,genres,rating,tmdbId,imdbId, poster)
            VALUES ('".$title."',".$year.",'".$genres."',".$rating.",".$tmdbId.",".$imdbId.",'".$poster."')";

    // check if record inserted:
    if ($connection->query($sql) === TRUE) {
        header("location: admin_movies.php");
        exit();}
    else {
        echo "<script>console.log(". json_encode( $sql).");</script>";
        echo "<script>alert('Something went wrong. Please try again.');</script>";
    }

    mysqli_close($connection);

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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" type="text/css" href="../style_main.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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
                        <a class="nav-link" href="admin_movies.php">Admin</a>
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
        <h4 style="color:#979797;font-weight:200">Add Record</h4>
        <form style="padding: 2em;" method="post">
            <div class="form-group row">
                <label for="inputTitle" class="col-2 col-form-label">Title</label>
                <input id="inputTitle" type="text" name="title" class="col-8 form-control" value="<?php echo $title; ?>" required>
            </div>

            <div class="form-group row">
                <label for="inputYear" class="col-2 col-form-label">Year</label>
                <input id="inputYear" type="text" name="year" class="col-8 form-control" value="<?php echo $year; ?>" required>
            </div>

            <div class="form-group row">
                <label for="inputGenres" class="col-2 col-form-label">Genres</label>
                <input id="inputGenres" type="text" name="genres" class="col-8 form-control" value="<?php echo $genres; ?>" required>
            </div>

            <div class="form-group row">
                <label for="inputRating" class="col-2 col-form-label">Rating</label>
                <input id="inputRating" type="number" step="any" name="rating" class="col-8 form-control" value="<?php echo $rating; ?>" required>
            </div>

            <div class="form-group row">
                <label for="inputtmdbId" class="col-2 col-form-label">tmdbId</label>
                <input id="inputtmdbId" type="number" name="tmdbId" class="col-8 form-control" value="<?php echo $tmdbId; ?>" required>
            </div>

            <div class="form-group row">
                <label for="inputimdbId" class="col-2 col-form-label">imdbId</label>
                <input id="inputimdbId" type="number" name="imdbId" class="col-8 form-control" value="<?php echo $imdbId; ?>" required>
            </div>

            <div class="form-group row">
                <label for="inputPoster" class="col-2 col-form-label">Poster</label>
                <input id="inputPoster" type="url" name="poster" class="col-8 form-control" value="<?php echo $poster; ?>" required>
            </div>

            <BR>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="admin_movies.php" class="btn btn-default">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>