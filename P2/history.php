<?php
	require_once 'base/header.php';
	require_once 'php/history.php';

	session_start();


	$movies = getHistory("users/".$_SESSION['email']);

	var_dump($movies);

	require_once 'base/footer.php';
?>