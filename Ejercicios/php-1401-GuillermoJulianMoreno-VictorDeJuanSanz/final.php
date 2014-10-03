<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Servicio</title>
</head>
<body>
	<? if($_POST["accept"]) { ?>
	<p>Bienvenido <?= $_COOKIE["name_title"] ?> a nuestro servicio.</p>
	<? } else { ?>
	<p>Lamentamos su decisión porque entonces no podremos ofrecerle el servicio.</p>
	<? } ?>
</body>
</html>