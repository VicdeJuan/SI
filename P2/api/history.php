<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	asdf
	<?php
	echo "olakase";
	require_once '../php/history.php';
	echo "olakase";

	/*createHistory("../users/","history.xml");*/

	/* Get */
	$result = getHistory("../users/","history.xml");
	if ($result == null)
		echo "SII";
	else
		print(count($result)."\n");

	
	/* Add */
	$movies_id = file_get_contents('php://input');

	$ids = array(array('id' => 0,'cuantity' => 7),array('id' => 7,'cuantity' => 3));

	$ids = json_decode(json_encode($ids),1);

	print("<br><br><br>Aqui es donde mirar: <br>");
	print_r(addHistory("../users/","history.xml",$ids));

	$result = getHistory("../users/","history.xml");



	?>

</body>
</html>