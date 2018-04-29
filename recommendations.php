<?php
session_start();
require('console_log.php');
require('connect.php');
require('api_keys.php');


function getMovieID($tmdbId,$title,$vote,$year){
    // add movie if doesnt exists

    $sql = "SELECT movieId FROM movies WHERE tmdbId = ". $tmdbId;
    $result = mysqli_fetch_assoc(mysqli_query($connection, $sql));
    $result = $result["movieId"];
    //console_log(strlen($result)===0);

    if(strlen($result)===0){
        // movie id's
        $imdbId = 0;
        $sql1 = "INSERT INTO movies (title, imdbId, tmdbId, rating, year)
            VALUES ('". $title ."',". $imdbId .", ". $tmdbId .", ". $vote .", ". $year .")";
        if ($connection->query($sql1) === TRUE) {
            $sql = "SELECT movieId FROM movies WHERE tmdbId = ". $tmdbId;
            $result = mysqli_fetch_assoc(mysqli_query($connection, $sql));
            $result = $result["movieId"];
        } else {
            $result = "error!";
            console_log(json_encode($sql1));
        }
    } else {
        $sql_rating = "UPDATE movies SET rating=".$vote.", WHERE movieId=".$result;
        $connection->query($sql_rating);
    }

    mysqli_close($connection);
    return $result;
}

function check($id){
    $username = $_SESSION['username'];
    $sql = "SELECT count(m.tmdbId) as n
            FROM movies as m, ratings as r 
            WHERE m.movieId = r.movieId
            AND r.username = '". $username ."'
            AND m.tmdbId = ". $id ." ";

    $result = mysqli_fetch_assoc(mysqli_query($connection, $sql));
    $result = (int)$result["n"];
    mysqli_close($connection);
    return $result;
}

// select 3 random movies and get recommendations
for ($x = 1; $x <= 3; $x++) {
    $sql = "SELECT m.tmdbId, m.title, m.rating
        FROM movies as m, ratings as r 
        WHERE m.movieId = r.movieId
        AND r.username = '". $username ."' 
        ORDER BY RAND() LIMIT 1";
    $result = mysqli_fetch_assoc(mysqli_query($connection, $sql));
    $movieId = trim($result["tmdbId"]);
    mysqli_close($connection);

    if($movieId!=NULL){
        $url = "https://api.themoviedb.org/3/movie/". $movieId ."/recommendations?page=1&language=en-US&api_key=".$api_key;
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
        $data[$x] = $response->results;
    } else {
        $data[$x] = [];
    }
}


// cleaning up the array (merging and removing duplicates)
$test[] = array_merge($data[1],$data[2],$data[3]);
$recomm = $test[0];

foreach ($recomm as $r){
    $tempArr[] = $r->id;
}
$tempArr = array_unique($tempArr);
$recomm = array_intersect_key($recomm, $tempArr);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recommendations</title>
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
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Recommendations</a>
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
        <h2>Recommendations</h2>
        <a href="#" onclick="location.reload();" style="color:#565656"><i class="fas fa-sync-alt"></i> Refresh</a><BR>
            <?php
                if ($err) {
                    console_log("cURL Error #:" . $err);
                } else {
                    //console_log($recomm);

                    foreach($recomm as $entry){
                        $id = (int)($entry->id);
                        if(check($id) === 0){
                            $year = $entry->release_date;
                            $year = (int)substr($year,0,4);
                            $poster = "http://image.tmdb.org/t/p/w200/". $entry->poster_path;
                            $vote = $entry->vote_average;
                            $title = $entry->original_title;

                            $t = getMovieID($id, $title, $vote, $year);
                            //echo '<a href="movie.php?id='. getMovieID($id, $title, $vote, $year) .'"><img style="margin:1em;" src="'. $poster .'"></a>';
                        
                        }
                    }
                }
            ?>
    </div>
</div>
</body>


</html>