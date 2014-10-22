<?php
function getMovies($from, $count)
{
	$movies = array(
		array( 
			'image' =>  '/img/movie.jpg',
			'title' =>  'Título',
			'description'=>  "Patatas fritsa",
			'genre'=>  'Terror',
			'price'=>  4
		),
		array( 
			'image' =>  '/img/movie.jpg',
			'title' =>  'Título123',
			'description'=>  "aaaaa fritsa",
			'genre'=>  'Patat',
			'price'=>  2
			),
		array( 
			'image' =>  '/img/movie.jpg',
			'title' =>  'Título 2 ',
			'description'=>  "Patatas asdasd",
			'genre'=>  "Comedia",
			'price'=>  1
		)
	);

	if($count < 3)
		$movies = array_slice($movies, 0, $count);

	return $movies;
}

$from = isset($_GET['from']) ? $_GET['from'] : 0;
$count = isset($_GET['count']) ? $_GET['count'] : 10;

$result = array(
	'movies' => getMovies($from, $count),
	'next' => $from + count
	);

echo json_encode($result);
?>