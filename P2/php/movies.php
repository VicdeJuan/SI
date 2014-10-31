<?php

function findMovie($movieList, $id)
{
	foreach ($movieList as $movie) {
		if ($movie['id'] === $id)
			return $movie;
	}

	return null;
}

function getAllMovies()
{
	$movies = simplexml_load_file("../data/movies.xml");

	return json_decode(json_encode($movies), true)['movie'];
}

function getMovies($from, $count)
{
	$movieArray = getAllMovies();
	
	$count = min($count, count($movieArray));
	$next = $from + $count;

	if($next >= count($movieArray))
		$next = -1;

	return array(
		'movies' => array_splice($movieArray, $from, $count),
		'next' => $next
	);
}
?>