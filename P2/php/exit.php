<!DOCTYPE html>
<html lang="en">
<head>
<body>
  <?php
  	require_once $_SERVER['DOCUMENT_ROOT'].'/php/common.php';

  	session_start();
  	unset($_SESSION['name']);
  	unset($_SESSION['email']);

    header("Location: ".$applicationBaseDir."index.php");
  ?>
</body>
</html>
