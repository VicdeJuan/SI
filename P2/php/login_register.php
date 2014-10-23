<!DOCTYPE html>
<html lang="en">
<head>
  <body>

    <?php
    /*  If we come from a registration  */
    $dir = "../users/".$_POST['email'];
    $filename = $dir."/"."datos.dat";
    if (!session_start())
      header("Location: exit.html");
    if (!is_dir($dir)){        /*User does not exist so we create it.*/
      if (!mkdir($dir,0777)){
        header("Location: ../pages/error.html");
        die();
      }
      $myfile = fopen($filename, "w");
      $txt = $_POST['name']."\n";
      $txt = $txt.$_POST['email']."\n";
      $txt = $txt.$_POST['password']."\n";
      $txt = $txt.$_POST['creditCard']."\n";
      $txt = $txt.$_POST['CSV']."\n";
      $txt = $txt.$_POST['expireDate']."\n";
      $txt = $txt.date('d/m/Y', time())."\n";
      fwrite($myfile, $txt);
      fclose($myfile);


      $name = $_POST['name'];
      $email = $_POST['email'];

      $_SESSION['name'] = $name;
      $_SESSION['email'] = $email;
      $_SESSION['ses'] = $name;

    }else{      /* Now, if we come from a login */
      $myfile = fopen($filename, "r");
      if ($myfile){
        $name=fgets($myfile);
        $email = fgets($myfile);
        $password = strstr(fgets($myfile),"\n",true);

        if (0 == strcmp($password,$_POST['password'])){
          /* Correct
          TODO: md5
          */
          $_SESSION['ses'] = $name;
          $_SESSION['name'] = $name;
          $_SESSION['email'] = $email;
        } else{
          session_start();
          $_SESSION['ses'] = "MISMATCH: ".$_POST['password']." & ".$password;
          $name="";
          /* Mismatch */
        }
        fclose($myfile);
      } else{
        session_start();
        $_SESSION['ses'] = "Registrarse";
      }
    }
    header("Location: ../index.php");
    ?>
  </body>
  </html>
