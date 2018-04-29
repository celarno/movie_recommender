<?php

$api_key = "ffaaa1ee77bee0d071c0a25df83b2567";
$tmdbId = $_GET['tmdbId'];

function console_log( $data ){
    echo '<script>';
    echo 'console.log('. $data .')';
    echo '</script>';
}

$url = "https://api.themoviedb.org/3/movie/". $tmdbId ."?api_key=".$api_key."&language=en-US";

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
curl_close($curl);

echo json_encode($response->poster_path);

?>