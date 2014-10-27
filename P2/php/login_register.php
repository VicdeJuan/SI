 <?php
/*  If we come from a registration  */

$log = fopen("log", "w");
if (isset($_POST['email'])) {
  $input = $_POST;
  fwrite($log, "_POST\n");
}else{
  $json = file_get_contents('php://input');
  $input = json_decode($json, true);      
  fwrite($log, "json\n");
}


$dir = "../users/".$input['email'];
fwrite($log,$input['email']."\n");
fwrite($log,$input['password']."\n");
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
  $txt = $input['name']."\n";
  $txt = $txt.$input['email']."\n";
  $txt = $txt.md5($input['password'])."\n";
  $txt = $txt.$input['creditCard']."\n";
  $txt = $txt.$input['CSV']."\n";
  $txt = $txt.$input['expireDate']."\n";
  $txt = $txt.date('d/m/Y', time())."\n";
  fwrite($myfile, $txt);
  fclose($myfile);
  fclose($log);


  $name = $input['name'];
  $email = $input['email'];

  $_SESSION['name'] = $name;
  $_SESSION['email'] = $email;

  header("Location: /index.php");
}else{      /* Now, if we come from a login */
  fwrite($log, "recibido: \n\tname:  ".$input['name']."\temail:  ".$input['email']."\tpassword:  ".$input['password']);
  $myfile = fopen($filename, "r");
  if ($myfile){
    $name = fgets($myfile);
    $email = fgets($myfile);
    $password = strstr(fgets($myfile),"\n",true);

    if (0 == strcmp($password,md5($input['password']))){
      $_SESSION['name'] = $name;
      $_SESSION['email'] = $email;
      $output = array( "name" => $name );
      echo json_encode($output);
      http_response_code(200);
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