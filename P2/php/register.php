<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Thank you</title>
</head>
<body>

  <?php
    $dir = "../users/".$_POST['email'];
    echo $_POST['email'];
    if (!is_dir($dir)){
      /*User does not exist so we create it.*/
      mkdir($dir,0777);
      $filename = $dir."/"."datos.dat";
      $myfile = fopen($filename, "w");
      $txt = $_POST['name']."!!name\n";
      $txt = $txt.$_POST['email']."!!email\n";
      $txt = $txt.$_POST['password']."!!password\n";
      $txt = $txt.$_POST['creditCard']."!!creditcardnumber\n";
      $txt = $txt.$_POST['CSV']."!!CSV\n";
      $txt = $txt.$_POST['expireDate']."!!expiredate\n";
      $txt = $txt.date('d/m/Y\0', time())."dateofregistering";
      fwrite($myfile, $txt);
      fclose($myfile);
    } else{
      /*User is already created.*/
      echo "Ya creado";
    }
    echo "<br>";
    ?>
    /*TODO: Redireccionar a la página principal*/
</body>
</html>
