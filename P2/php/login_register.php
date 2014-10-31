 <?php
/*  If we come from a registration  */

require_once $_SERVER['DOCUMENT_ROOT'].'/php/history.php';

if (isset($_POST['email'])) {
  $input = $_POST;
}else{
  $json = file_get_contents('php://input');
  $input = json_decode($json, true);      
}


$dir = $_SERVER['DOCUMENT_ROOT']."/users/".$input['email'];
$filename = $dir."/"."datos.dat";

if (!session_start())
  header("Location: exit.html");

if (!is_dir($dir) and isset($_POST['creditCard'])){        /*User does not exist so we create it.*/
  if (!mkdir($dir,0777)){
    header("Location: /pages/error.html");
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

  $name = $input['name'];
  $email = $input['email'];

  $_SESSION['name'] = $name;
  $_SESSION['email'] = $email;

  if(createHistory($dir) == 404)
    header("Location /dfmkals.html");

  header("Location: /index.php");
}else{      /* Now, if we come from a login */
  $myfile = fopen($filename, "r");
  if ($myfile){
    $name = fgets($myfile);
    $name = trim(preg_replace('/\s+/', ' ', $name));

    $email = fgets($myfile);
    $email = trim(preg_replace('/\s+/', ' ', $email));
    
    $password = strstr(fgets($myfile),"\n",true);
    $password = trim(preg_replace('/\s+/', ' ', $password));

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
      http_response_code(404);
    }
    fclose($myfile);

  } else{
    http_response_code(404); 
  }
}
?>