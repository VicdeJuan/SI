<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Servicio - Condiciones</title>
</head>
<body>
	<?php 
		if($_POST["sex"] == "male")
			$name_title = "Sr. ".$_POST["name"];
		else
			$name_title = "Sra. ".$_POST["name"];

		setcookie("name_title", $name_title, time() + 60 * 60, "/"); 
	?>
	<?php if($_POST["age"] > 18) { ?>
		<h3>Condiciones</h3>
		<p>
			<?php echo $name_title; ?>, ¿acepta las condiciones de uso de nuestro servicio?
		</p>
		<p>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
		</p>
		<form action="final.php" method="post">
			<input type="submit" value="Acepto" name="accept" />
			<input type="submit" value="No acepto" name="reject" />
		</form>
	<?php } else { ?>
		<p>No ofrecemos servicio a personas con 18 años o menos.</p>
	<?php } ?>
</body>
</html>