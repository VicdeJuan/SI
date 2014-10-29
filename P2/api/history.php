<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
	require_once '../php/history.php';

	session_start();

	/* Auxiliary function's definition */

	function get()
	{
		$clean_email = "../users/".$_SESSION['email'];
		echo json_encode(getHistory($clean_email));
	}

	function post()
	{
		$movies_ids = (array) json_decode(file_get_contents('php://input'),1)

		/* Este json contiene los id's de las peliculas */

		$clean_email = "../users/".$_SESSION['email'];
		addHistory($clean_email,$movies_ids);

	}

	/* Main process */
	switch($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		get();
		break;
	case 'POST':
		post();
		break;
	default:
		http_response_code(501);
};

?>	

</body>
</html>