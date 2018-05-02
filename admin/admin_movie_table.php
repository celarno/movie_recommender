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
    array( 'db' => 'rating',  'dt' => 4 ),
    array( 'db' => 'imdbId',  'dt' => 5 ),
    array( 'db' => 'tmdbId',  'dt' => 6 ),
    array( 'db' => 'poster',  'dt' => 7 ),
    array( 'db' => 'movieId', 'dt' => 8,
        'formatter' => function($t) {
            return "<a href='admin_edit_movie.php?id=". $t ."'   title='Edit Record'>edit</a> | 
                    <a href='admin_delete_movie.php?id=". $t ."' title='Delete Record'>delete</a>";
    })
);

// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => 'x8C3wEqZd4DdoxwP',
    'db'   => 'db_recomm',
    'host' => 'localhost'
);

require('../ssp.class.php');

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);