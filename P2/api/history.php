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

	/* Get */
	$result = getHistory("../users/","history.xml");
	if ($result == null)
		echo "SII";
	else
		print(count($result)."\n");

	/* Create */
	print(createHistory("../users/","asdf"));


	/* Add */
	$movies_id = file_get_contents('php://input');

	$ids = array(array('id' => 0,'cuantity' => 7),array('id' => 7,'cuantity' => 3));

	print("<br><br><br>Aqui es donde mirar: <br>");


	print_r(addHistory("../users/","asdf",$ids));


	?>

</body>
</html>