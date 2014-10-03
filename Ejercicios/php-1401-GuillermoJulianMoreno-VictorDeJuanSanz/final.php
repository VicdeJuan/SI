<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Servicio</title>
</head>
<body>
	<?php if($_POST["accept"]) { ?>
	<p>Bienvenido <?= $_COOKIE["name_title"] ?> a nuestro servicio.</p>
	<?php } else { ?>
	<p>Lamentamos su decisión porque entonces no podremos ofrecerle el servicio.</p>
	<?php } ?>
</body>
</html>