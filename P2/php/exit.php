<!DOCTYPE html>
<html lang="en">
<head>
<body>
  <?php
     session_unset();
     session_destroy();
     header("Location: ../index.php");
  ?>
</body>
</html>
