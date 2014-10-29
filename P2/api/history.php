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
	$result = getHistory("../users/","history.xml");
	if ($result == null)
		echo "SII";
	else
		print_r($result);

	?>

</body>
</html>