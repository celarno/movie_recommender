<?php
session_start();
require('console_log.php');
require('connect.php');
require('api_keys.php');

$sql = "SELECT DISTINCT m.movieId, m.tmdbId
        FROM db_user.ratings as r, db_recomm.movies as m
        WHERE r.username='".$username."'
        AND m.movieId = r.movieId
        ORDER BY r.rId ASC";

$query = $connection->query($sql);
while ($row = $query->fetch_assoc()) {
    $data[] = $row;
}
mysqli_close($connection);

// initialise single curls
foreach ($data as $d){
    $tmdbId = $d['tmdbId'];
    $url = "https://api.themoviedb.org/3/movie/". $tmdbId ."?api_key=".$api_key."&language=en-US";
    $curl[$tmdbId] = curl_init($url);
}



/*
 * API call is limit is 40 calls every 10 sec --> too slow ...
// adding all curls to multi-curl handle
$mh = curl_multi_init();
foreach ($curl as $c){
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_multi_add_handle($mh, $c);
}

// execute all queries simultaneously
$running = null;
do {
    curl_multi_exec($mh, $running);
} while ($running);


foreach ($data as &$d){
    $p = json_decode(curl_multi_getcontent($curl[$d['tmdbId']]));
    $poster = "http://image.tmdb.org/t/p/w200/". $p->poster_path;
    $d['tmdbId'] = $poster;
}

// remove all handles
foreach ($curl as $c){
    curl_multi_remove_handle($mh, $c);
}

curl_multi_close($mh);

*/

echo json_encode($data);

?>