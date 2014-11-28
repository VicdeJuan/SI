<?php

require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/php/common.php";
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/php/sql.php";

function findMovie($movieList, $id)
{
	foreach ($movieList as $movie)
	{
		if ($movie['id'] === $id)
			return $movie;
	}

	return null;
}

function removeNumericValue($row)
{
	foreach($row as $key => $value)
	{
		if(is_numeric($key))
			unset($row[$key]);
	}

	return $row;
}

function getMovies($from, $count)
{
	$pdo = DBConnect_PDO();

	$query = <<<SQL
SELECT * FROM (
	SELECT * FROM(
		SELECT imdb_movies.movieid AS id, movietitle AS title, year, string_agg(genre, ',')
			AS genre, prod_id, price, url_to_img as image
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
SQL;

	$stmt = $pdo->prepare($query);
	$count_end = $from + $count;
	$stmt->bindParam(':count_end', $count_end);
	$stmt->bindParam(':page_size', $count);

	$movies = stmtQuery($stmt);

	$dbMovieCount = getTableRowCount($pdo, "imdb_movies");

	$count = min($count, $dbMovieCount);
	$next = $from + $count;

	if($next >= $dbMovieCount)
		$next = -1;

	return array(
		'movies' => array_map("removeNumericValue", $movies),
		'next' => $next
	);
}
?>
