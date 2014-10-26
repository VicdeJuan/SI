<?php

require_once '../php/movies.php';

$movies = getAllMovies();

$genres = [];

foreach ($movies as $movie) {
	$genre = $movie['genre'];

	if(!in_array($genre, $genres))
		array_push($genres, $genre);
}

echo json_encode($genres);
?>