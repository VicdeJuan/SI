<?php

require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/php/movies.php';
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/php/sql.php";

$pdo = DBConnect_PDO();

$stmt = $pdo->prepare("SELECT genre FROM genres;");

$genres = stmtQuery($stmt);

function get_genre($row)
{
	return $row['genre'];
}

echo json_encode(array_map("get_genre", $genres));

?>
