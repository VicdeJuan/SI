<?php

require_once '../php/movies.php';

$from = isset($_GET['from']) ? $_GET['from'] : 0;
$count = isset($_GET['count']) ? $_GET['count'] : 10;

echo json_encode(getMovies($from, $count));

?>