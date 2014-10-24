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
      $txt = $txt.md5($_POST['password'])."\n";
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

    }else{      /* Now, if we come from a login */
      $myfile = fopen($filename, "r");
      if ($myfile){
        $name = fgets($myfile);
        $email = fgets($myfile);
        $password = strstr(fgets($myfile),"\n",true);

        if (0 == strcmp($password,md5($_POST['password']))){
          $_SESSION['name'] = $name."olakase";
          $_SESSION['email'] = $email;
        } else{
          /* Mismatch */
          $_SESSION['name'] = "Mismatch";
          $_SESSION['email'] = "";
          $name="";
        }
        fclose($myfile);
      } else{
        /* Return 404 */
      }
    }
    header("Location: /index.php");
    ?>
  </body>
  </html>
