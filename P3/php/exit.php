<!DOCTYPE html>
<html lang="en">
<head>
<body>
  <?php
  	require_once dirname(__FILE__).'/common.php';

  	session_start();
  	unset($_SESSION['name']);
  	unset($_SESSION['email']);

    header("Location: ".$applicationBaseDir."index.php");
  ?>
</body>
</html>
