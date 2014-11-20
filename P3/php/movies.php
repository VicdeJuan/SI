<?php

require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/php/common.php";

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


	foreach ($movies as &$movie) {
		$movie['image'] = asAbsoluteUrl($movie['image']);
	}

	return $movies;
}

function getMovies($from, $count)
{
	$db_username = "alumnodb";
	$db_password = "alumnodb";

	$dbh = new PDO( "pgsql:dbname=olakase; host=localhost", $db_username, $db_password) ;

	$query = <<<SQL
SELECT * FROM (
	SELECT * FROM(
		SELECT imdb_movies.movieid as id, movietitle as title, year, string_agg(genre, ',') as genre, prod_id, price
			FROM imdb_movies
			JOIN imdb_moviegenres ON imdb_moviegenres.movieid = imdb_movies.movieid
			JOIN products on products.movieid = imdb_movies.movieid
			JOIN genres on genres.genreid = imdb_moviegenres.genreid
			GROUP BY imdb_movies.movieid, movietitle, year, prod_id, price
			ORDER BY imdb_movies.movieid
			LIMIT :count_end) AS cut_end
		ORDER BY id DESC
		LIMIT :page_size) as cut_begin
	ORDER BY id;
SQL
	
	
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':count_end', $from + $count);
	$stmt->bindParam(':page_size', $count);

	$stmt->execute();	
	
	$dbMovieCount = getTableRowCount($dbh, "imdb_movies");

	$count = min($count, $dbMovieCount);
	$next = $from + $count;

	if($next >= $dbMovieCount)
		$next = -1;

	return array(
		'movies' => json_decode(json_encode($stmt->fetchAll())),
		'next' => $next
	);
}
?>