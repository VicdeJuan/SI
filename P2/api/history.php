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
		if (isset($_SESSION['email'])) {
			$clean_email = "../users/".$_SESSION['email'];
			echo json_encode(getHistory($clean_email));
		}else{
			http_response_code(200);
		}
	}

	function post()
	{


		$json = file_get_contents('php://input');

		$movies_ids = json_decode($json,true);

		$clean_email = "../users/".$_SESSION['email']."/";
		if (addHistory($clean_email,$movies_ids) == null)
			http_response_code(404);

		http_response_code(200);
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