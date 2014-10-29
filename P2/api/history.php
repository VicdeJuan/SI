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

	/* Main process */
	switch($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		get();
		break;
	case 'POST':
		post();
		break;
	case 'DELETE':
		delete();
		break;
	default:
		http_response_code(501);
};

?>	

</body>
</html>