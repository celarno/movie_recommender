<?php
session_start();
$username = $_SESSION['username'];

// Table's primary key
$primaryKey = 'movieId';

$table = <<<EOT
(
    SELECT m.movieId, m.title, m.year, m.genres, m.rating, m.poster as cover
    FROM movies as m
    INNER JOIN ratings as r ON m.movieId = r.movieId
    AND r.username = '{$username}'
) temp
EOT;

// columns with formatting
$columns = array(
    array( 'db' => 'movieId', 'dt' => 0 ),
    array( 'db' => 'title',   'dt' => 1,
        'formatter' => function($t) {
            return '<h5>'.$t.'</h5>';
        }),
    array( 'db' => 'year',    'dt' => 2 ),
    array( 'db' => 'genres',  'dt' => 3,
        'formatter' => function($g) {
            return str_replace("|"," | ",$g);
        }),
    array( 'db' => 'rating',  'dt' => 4,
        'formatter' => function($s) {
            $rating = $s;
                if($rating >= 8){
                    $stars = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
                } elseif ($rating >= 6 && $rating < 8) {
                    $stars = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>';
                } elseif ($rating > 4.5 && $rating < 6) {
                    $stars = '<i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                } else {
                    $stars = '<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                }
            return $stars."<BR>(".$rating.")";
        }
    ),
    array( 'db' => 'cover',   'dt' => 5,
        'formatter' => function($d) {
                            return '<img style="height:30%;filter:grayscale(80%)" src="'.$d.'">';
                })
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