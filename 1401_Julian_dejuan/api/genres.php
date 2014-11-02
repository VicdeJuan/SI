<?php

require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/php/movies.php';

$movies = getAllMovies();

$genres = [];

foreach ($movies as $movie) {
	$genre = $movie['genre'];

	if(!in_array($genre, $genres))
		array_push($genres, $genre);
}

echo json_encode($genres);
?>