<!DOCTYPE html>
<html lang="en">
<head>
<body>
  <?php
  	session_start();
     session_unset();


     if (session_destroy())
     	header("Location: /index.php");
     else
     	header("Location: /pages/error.html")
  ?>
</body>
</html>
