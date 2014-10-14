<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Thank you</title>
</head>
<body>

  <?php
    $dir = "users/".$_POST['email'];
    echo $_POST['email'];
    if (!is_dir($dir)){
      /*User does not exist so we create it.*/
      mkdir($dir,0777);
      $filename = $dir."/"."datos.dat";
      $myfile = fopen($filename, "w");
      $txt = $_POST['name']."\n";
      fwrite($myfile, $txt);
      $txt = $_POST['email']."\n";
      fwrite($myfile, $txt);
      $txt = $_POST['password']."\n";
      fwrite($myfile, $txt);
      $txt = $_POST['creditCard']."\n";
      fwrite($myfile, $txt);
      $txt = $_POST['CSV']."\n";
      fwrite($myfile, $txt);
      $txt = $_POST['expireDate']."\n";
      fwrite($myfile, $txt);
      $txt = date('d/m/Y', time());
      fwrite($myfile, $txt);
      fclose($myfile);
    } else{
      /*User is already created.*/
      echo "Ya creado";
    }
    echo "<br>";
    ?>
</body>
</html>
