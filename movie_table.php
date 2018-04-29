<?php
session_start();

// DB table to use
$table = 'movies';

// Table's primary key
$primaryKey = 'movieId';

$columns = array(
    array( 'db' => 'movieId', 'dt' => 0 ),
    array( 'db' => 'title',   'dt' => 1 ),
    array( 'db' => 'year',    'dt' => 2 ),
    array( 'db' => 'genres',  'dt' => 3 ),
    array( 'db' => 'rating',  'dt' => 4 )
);

// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => 'x8C3wEqZd4DdoxwP',
    'db'   => 'db_recomm',
    'host' => 'localhost'
);

require('ssp.class.php');

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);