<?php
	require_once dirname(__FILE__).'/../php/history.php';

	session_start();

	/* Auxiliary function's definition */

	function get()
	{
		if (isset($_SESSION['email'])) {
			echo json_encode(getHistory());
		}else{
			http_response_code(200);
		}
	}

	function post()
	{
		$json = file_get_contents('php://input');

		$cart = json_decode($json, true);

		$clean_email = dirname(__FILE__).'/../users/'.$_SESSION['email']."/";

		if (addHistory($clean_email, $cart) == null)
			http_response_code(404);

		http_response_code(200);
	}

	if(!isset($_SESSION['id']))
	{
		echo '{ "error": "Not logged in." }';
		http_send_status(403);
		return;
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
