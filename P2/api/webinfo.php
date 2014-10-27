<?php
	$info = array(
		"date" => date('l jS F Y, h:i:s A'),
		"active_users" => rand()
		);

	echo json_encode($info)

?>