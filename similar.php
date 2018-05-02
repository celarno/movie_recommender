<?php
session_start();
require('console_log.php');
require('api_keys.php');
require('connect.php');

$id = $_GET['id'];

$sql = "SELECT title FROM movies WHERE tmdbId = ". $id;
$result = mysqli_fetch_assoc(mysqli_query($connection, $sql));
$t = $result["title"];

function getMovieID($tmdbId,$title,$vote,$year,$poster){

    require('connect.php');
    $poster = iconv('ASCII', 'UTF-8//IGNORE', $poster);

    // get movieID
    $sql = "SELECT movieId FROM movies WHERE tmdbId = ". $tmdbId;
    $result = mysqli_fetch_assoc(mysqli_query($connection, $sql));
    $result = $result["movieId"];

    // if movie does not exists, add to db
    if(strlen($result)==0){
        $sql1 = 'INSERT INTO movies (title, tmdbId, rating, year, poster)
            VALUES ("'. $title .'", '. $tmdbId .', '. $vote .', '. $year .', "'. $poster. '")';

        if ($connection->query($sql1) === TRUE) {
            $sql = "SELECT movieId FROM movies WHERE tmdbId = ". $tmdbId;
            $result = mysqli_fetch_assoc(mysqli_query($connection, $sql));
            $result = $result["movieId"];
        } else {
            $result = "";
            console_log($result);
        }
    } else {
        // update rating
        $sql_rating = "UPDATE movies SET rating=".$vote.", WHERE movieId=".$result;
        $connection->query($sql_rating);
    }

    mysqli_close($connection);
    return $result;
}


if($id!=NULL){
    $recomm = [];
    $page = rand(1,3);
    $url = "https://api.themoviedb.org/3/movie/". $id ."/similar?page=".$page."&language=en-US&api_key=".$api_key;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "{}",
    ));
    $response = json_decode(curl_exec($curl));
    $err = curl_error($curl);
    curl_close($curl);
    $recomm = $response->results;
} else {
    console_log("Error!");
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Similar Movies</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>

    <link rel="stylesheet" type="text/css" href="style_main.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/r-2.2.1/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/r-2.2.1/datatables.min.js"></script>

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
                    <li class="nav-item">
                        <a class="nav-link" href="myprofile.php">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recommendations.php">Recommendations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="browse.php">Browse Movies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/admin_movies.php">Admin</a>
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
        <h2>Similar Movies</h2>
        <?php
        echo "<h3 style='color:#979797'>" . $t ."</h3>";
        echo "";
        if ($err) {
            console_log("cURL Error #:" . $err);
        } else {
                foreach($recomm as $entry){
                    $tmdbId = (int)($entry->id);
                    $year = $entry->release_date;
                    $year = (int)substr($year,0,4);
                    $poster = "http://image.tmdb.org/t/p/w200/". $entry->poster_path;
                    $vote = $entry->vote_average;
                    $title = $entry->original_title;
                    echo '<a href="movie.php?id='. getMovieID($tmdbId, $title, $vote, $year, $poster) .'"><img style="margin:1em;" src="'. $poster .'"></a>';
                }
        }
        ?>
    </div>
</div>
</body>


</html>