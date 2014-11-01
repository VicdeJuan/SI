<?php

require_once $_SERVER['DOCUMENT_ROOT']."/php/common.php";

function findMovie($movieList, $id)
{
	foreach ($movieList as $movie) 
	{
		if ($movie['id'] === $id)
			return $movie;
	}

	return null;
}

function getAllMovies()
{
	$path = $_SERVER['DOCUMENT_ROOT']."/data/movies.xml";
	$movies = json_decode(json_encode(simplexml_load_file($path)), true)['movie'];

	foreach ($movies as &$movie) {
		$movie['image'] = asAbsoluteUrl($movie['image']);
	}

	return $movies;
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