<!DOCTYPE html>
<html lang="en">
<head>
  <body>

    <?php
    /*  If we come from a registration  */
    if (empty($_GET)) {
      http_response_code(402);
    }


    $dir = "../users/".$_GET['email'];
    $log = fopen("log", "w");
    fwrite($log,$_GET['email']."\n");
    fwrite($log,$_GET['password']."\n");
    $filename = $dir."/"."datos.dat";
    if (!session_start())
      header("Location: exit.html");
    if (!is_dir($dir)){        /*User does not exist so we create it.*/
      if (!mkdir($dir,0777)){
        header("Location: /pages/error.html");
        $error = error_get_last();
        fwrite($log, "error: ".$error['message']);
        fclose($log);
        die();
      }
      $myfile = fopen($filename, "w");
      $txt = $_GET['name']."\n";
      $txt = $txt.$_GET['email']."\n";
      $txt = $txt.md5($_GET['password'])."\n";
      $txt = $txt.$_GET['creditCard']."\n";
      $txt = $txt.$_GET['CSV']."\n";
      $txt = $txt.$_GET['expireDate']."\n";
      $txt = $txt.date('d/m/Y', time())."\n";
      fwrite($myfile, $txt);
      fclose($myfile);
      fclose($log);


      $name = $_GET['name'];
      $email = $_GET['email'];

      $_SESSION['name'] = $name;
      $_SESSION['email'] = $email;

      header("Location: /index.php");
    }else{      /* Now, if we come from a login */
      fwrite($log, "recibido: \n\tname:  ".$_GET['name']."\temail:  ".$_GET['email']."\tpassword:  ".$_GET['password']);
      $myfile = fopen($filename, "r");
      if ($myfile){
        $name = fgets($myfile);
        $email = fgets($myfile);
        $password = strstr(fgets($myfile),"\n",true);

        if (0 == strcmp($password,md5($_GET['password']))){
          $_SESSION['name'] = $name;
          $_SESSION['email'] = $email;
          http_response_code(202);
        } else{
          $_SESSION['name'] = "Mismatch";
          $_SESSION['email'] = "";
          $name="";
          http_response_code(417);
        }
        fclose($myfile);
        fclose($log);

      } else{
        http_response_code(405); 
      }
    }
    ?>
  </body>
  </html>
