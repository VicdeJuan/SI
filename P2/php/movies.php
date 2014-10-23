<?php
function getMovies($from, $count)
{
	$movies = simplexml_load_file("../data/movies.xml");

	$movieArray = json_decode(json_encode($movies), true)['movie'];

	$count = min($count, count($movieArray));

	return array_splice($movieArray, $from, $count);
}

$from = isset($_GET['from']) ? $_GET['from'] : 0;
$count = isset($_GET['count']) ? $_GET['count'] : 10;

$result = array(
	'movies' => getMovies($from, $count),
	'next' => $from + count
	);

echo json_encode($result);
?>