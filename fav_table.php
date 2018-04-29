<?php
session_start();

// Table's primary key
$primaryKey = 'movieId';

$table = <<<EOT
(
    SELECT m.movieId, m.title, m.year, m.genres, m.rating, r.rating as favorite
    FROM movies as m
    INNER JOIN ratings as r ON m.movieId = r.movieId
    AND r.username = 'test'
) temp
EOT;


// columns
$columns = array(
    array( 'db' => 'movieId', 'dt' => 0 ),
    array( 'db' => 'title',   'dt' => 1 ),
    array( 'db' => 'year',    'dt' => 2 ),
    array( 'db' => 'genres',  'dt' => 3 ),
    array( 'db' => 'rating',  'dt' => 4 ),
    array( 'db' => 'favorite',  'dt' => 5 )
);

// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => 'x8C3wEqZd4DdoxwP',
    'db'   => 'db_recomm',
    'host' => 'localhost'
);

require( 'ssp.class.php' );

echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);