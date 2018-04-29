<?php
session_start();
require('connect.php');

//get search term
$searchTerm = $_GET['term'];

//get matched data from movie table
$query = $connection->query("SELECT m.title FROM movies as m
                                      LEFT OUTER JOIN ratings as r 
                                      ON m.movieId = r.movieId 
                                      AND r.username = '".$username."'
                                    WHERE r.rating IS NULL
                                    AND m.title LIKE '%".$searchTerm."%' 
                                    ORDER BY m.rating DESC LIMIT 10");

while ($row = $query->fetch_assoc()) {
    $data[] = $row['title'];
}

mysqli_close($connection);

//return json data
echo json_encode($data);
?>