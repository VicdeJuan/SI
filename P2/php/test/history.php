
	<?php
	require_once '../../php/history.php';

	/*$movies_id = file_get_contents('php://input');*/
	/*createHistory("../users/","history.xml");*/

	/* Get */
	/*print(is_file("../../users/victor@qwerty"));*/
	/*$result = getHistory("../../users/victor@qwerty");*/

	
	/* Add */

	session_start();

	/* print_r(addHistory("../../users/victor@qwerty",$ids));*/

	print_r(getHistory("../../users/".$_SESSION['email']));



	?>