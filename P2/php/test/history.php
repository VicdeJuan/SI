<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	asdf
	<?php
	require_once '../../php/history.php';

	/*$movies_id = file_get_contents('php://input');*/
	/*createHistory("../users/","history.xml");*/

	/* Get */
	/*print(is_file("../../users/victor@qwerty"));*/
	/*$result = getHistory("../../users/victor@qwerty");*/

	
	/* Add */

	$ids = array(array('id' => 0,'quantity' => 7),array('id' => 7,'quantity' => 3));

	print_r($ids);
	print("<br><br><br>Aqui es donde mirar: <br>");
	


	print_r(addHistory("../../users/victor@qwerty",$ids));

	/*print_r(getHistory("../users/victor@qwerty"));*/



	?>

</body>
</html>