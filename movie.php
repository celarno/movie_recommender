<?php
session_start();
require('console_log.php');
require('api_keys.php');
require('connect.php');

$movieid = $_GET['id'];

function curly($url) {
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
    $r = json_decode(curl_exec($curl));
    curl_close($curl);
    return $r;
}

// get tmdbId for movieDB queries
$sql = "SELECT tmdbId, imdbId FROM movies WHERE movieId=".$movieid;
$result = trim(mysqli_fetch_assoc(mysqli_query($connection, $sql))["tmdbId"]);
$idmb_id_2 = trim(mysqli_fetch_assoc(mysqli_query($connection, $sql))["imdbId"]);

// get movie details - part 1
$url = "https://api.themoviedb.org/3/movie/". $result ."?language=en-US&api_key=".$api_key;
$response = curly($url);

if($r->status_code===34){
    $r = "ERROR";
}


$imdb_id = $response->imdb_id;
$title = $response->title;
$plot = $response->overview;
$rating = $response->vote_average;

if($rating >= 8){
    $stars = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
} elseif ($rating >= 6 && $rating < 8) {
    $stars = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>';
} elseif ($rating > 4.5 && $rating < 6) {
    $stars = '<i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
} else {
    $stars = '<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
}

$year = $response->release_date;
$release_date = (int)substr($year,0,4);
$imdb_url = "http://www.imdb.com/title/".$imdb_id;
$poster = "http://image.tmdb.org/t/p/w200/". $response->poster_path;
$genres = [];
foreach ($response->genres as $g){
    array_push($genres, $g->name);
}
$genres = implode("|", $genres);

// get movie details - Crew and Cast
$url = "https://api.themoviedb.org/3/movie/". $result ."/credits?api_key=".$api_key;
$response = curly($url);
$writer = []; $writer_pics = [];
$director = [];$director_pics = [];
foreach($response->crew as $c){
    if($c->job == "Screenplay" || $c->job == "Writer"){
        array_push($writer, $c->name);
        array_push($writer_pics, "http://image.tmdb.org/t/p/w200/".$c->profile_path);
    }
    if($c->job == "Director"){
        array_push($director, $c->name);
        array_push($director_pics,"http://image.tmdb.org/t/p/w200/".$c->profile_path);
    }
}
$cast = []; $cast_pics = [];
$cast_array = array_slice($response->cast, 0, 5);
foreach ($cast_array as $c){
    array_push($cast, $c->name);
    array_push($cast_pics, "http://image.tmdb.org/t/p/w200/".$c->profile_path);
}

// get movie trailer
$url = "https://api.themoviedb.org/3/movie/". $result ."/videos?api_key=".$api_key;
$response = curly($url);
$videos = $response->results;
$trailer = "";
foreach ($videos as $v) {
    if($v->site == "YouTube" && $v->type=="Trailer"){
        $trailer = $v->key;
        break;
    }
}

if($trailer == ""){
    $q = $title." ".$release_date." trailer";
    $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&q=".$q."&type=video&key=".$youtube_key;
    $url = str_replace(" ","%20",$url);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $response = json_decode(curl_exec($curl));
    $error = curl_error($curl);
    curl_close($curl);
    console_log($error);

    $videos = $response->items;
    $trailer = $videos[0]->id;
    $trailer = $trailer->videoId;
}

// update row in table with imdb_id
$sql = "UPDATE movies
        SET title='".$title."', rating=". $rating. ", imdbId=".(int)substr($imdb_id,2).", year=". $release_date.", genres='". $genres."'
        WHERE movieId=".$movieid.";";
$connection->query($sql);

// check if movie is favorite
$sql = "SELECT count(movieId) as n
            FROM ratings as r
            WHERE r.movieId = ". $movieid ."
            AND r.username = '". $username ."'";
$fav = mysqli_fetch_assoc(mysqli_query($connection, $sql));
$fav = $fav["n"];

mysqli_close($connection);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Browse Movies</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="myprofile.php">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recommendations.php">Recommendations</a>
                    </li>
                    <li class="nav-item active">
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
        <!--<p><a href="browse.php" style="color:#9a9a9a"><i class="fas fa-angle-double-left"></i> back</a></p>-->

    <?php

    if ($r !== "ERROR") {

        echo '<h2>' . $title. '</h2><br>';
        echo '<table id="movie_table">';
        echo '<tr><td><i class="fas fa-calendar-alt"></i><b> Release Year</b></td><td>' . $release_date. '</td>';
        echo '<td rowspan=8 id="movie_r">
                <img src="'. $poster .'"><br><br>
                <li><a id="remove" style="display:none;" href="#"><i class="fas fa-trash"></i> Remove from favorites</a></li>
                <li><a id="adding" style="display:none;" href="#"><i class="fas fa-plus-circle"></i> Add to favorites</a><div id="results"></div></li>
                <li><a target="_blank" href="'. $imdb_url .'"><i class="fab fa-imdb"></i> IMDB page</a></li>
                <li><a id="stars" href="#">'. $stars .' ('.$rating.')</a></li>
                </td></tr>';

        echo '<tr><td><i class="fas fa-book"></i><b> Plot</b></td><td>' . $plot . '</td></tr>';
        echo '<tr><td><i class="fas fa-video"></i><b> Director(s)</b></td><td>' . implode(", ", $director) . '</td></tr>';
        echo '<tr><td><i class="fas fa-pencil-alt"></i><b> Writer(s)</b></td><td>' . implode(", ", $writer) . '</td></tr>';
        echo '<tr><td><i class="fas fa-users"></i><b> Cast</b></td><td>' . implode(", ",$cast) . '</td></tr>';
        echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
        echo '<tr><td></td><td><iframe id="ytplayer" type="text/html" width="640" height="360" allowfullscreen
                    src="https://www.youtube.com/embed/'. $trailer .'" frameborder="0"></iframe></td></tr>';
        echo '</table>';

    } else {
        echo '<p>No movie found.</p>';
    }

    ?>

    </div>
</div>

<script>
    $(document).ready(function(){

        var fav = <?php echo $fav ?>;
        if(fav === 0){
            $("#adding").show();
        } else {
            $("#remove").show();
        }

        // check if everything is loaded
        if($("#movie_table > tbody > tr:nth-child(1) > td:nth-child(2)").text()==="0"){
            location.reload();
        }

        $("#adding").click(function() {
            $.ajax({
                type: "POST",
                url: "update_fav.php",
                data: {fav: <?php echo $movieid ?>},
                dataType: "JSON",
                success: function(data) {
                    $("#results").html(data);
                    location.reload();
                },
                error: function(err) {
                    $("#results").html(data);
                }
            });
        });

        $("#remove").click(function () {
            $.ajax({
                type: "POST",
                url: "remove_fav.php",
                data: {fav:<?php echo $movieid ?>},
                dataType: "JSON",
                success: function(data) {
                    $("#results").html(data);
                    location.reload();
                },
                error: function(err) {
                    $("#results").html(data);
                }
            });
        });
    });
</script>
</body>
</html>
