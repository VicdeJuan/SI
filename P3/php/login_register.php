 <?php
/*  If we come from a registration  */

require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/php/common.php';
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/php/history.php';
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/php/sql.php';

/*
Campos de customers: 
customerid , firstname , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" 
 */


$dbh =  DBConnect_PDO();


$stmt = $dbh->prepare( "SELECT email,username,password FROM customers WHERE email = :email AND password = :password" );
$stmt_create = $dbh->prepare( "INSERT INTO customers (
    firstname ,lastname ,address1 ,address2 ,city ,state ,ip ,country,region,email ,phone ,
    creditcardtype ,creditcard ,creditcardexpiration ,username ,password ,age ,income ,gender)
  values 
    (:username ,'lastname' ,'address1' ,'address2' ,'city' ,'state' ,28030 ,'country' ,'region',
     :email ,'phone' ,'cctype' ,
     :creditCard,
     :expireCard ,
     :username ,
     :password ,21 ,5 ,'M'); 
  " );
$stmt_getid = $dbh->prepare( "SELECT customerid as id, username as name  FROM customers WHERE email = :email" );




if (isset($_POST['email'])) {
  $input = $_POST;
}else{
  $json = file_get_contents('php://input');
  $input = json_decode($json,true);      
}

/* Exeute query */
$email = $input['email'];
$password = $input['password'];

$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->bindParam(':password', $password, PDO::PARAM_STR);

$stmt->execute();
$result = $stmt->fetchAll();


if (!session_start())
  header("Location: ".$applicationBaseDir."exit.html");

if (count($result) == 0 && isset($_POST['creditCard'])){        /*User does not exist so we create it.*/

  $stmt_create->bindParam(':username',$name,PDO::PARAM_STR);
  $stmt_create->bindParam(':email',$email,PDO::PARAM_STR);
  $stmt_create->bindParam(':creditCard',$input['creditCard'],PDO::PARAM_STR);
  $stmt_create->bindParam(':expireCard',$input['expireDate'],PDO::PARAM_STR);
  $stmt_create->bindParam(':password',$password,PDO::PARAM_STR);

  $stmt_create->execute();


  $_SESSION['name'] = $name;
  $_SESSION['email'] = $email;
  $stmt_getid->bindParam(':email',$email,PDO::PARAM_STR);

  $result_id_name = $stmtQuery($stmt_getid);

  $_SESSION['id'] = $result_id_name[0]['id'];
  $_SESSION['name'] = $result_id_name[0]['name'];


  header("Location: ".$applicationBaseDir."index.php");
}else{      /* Now, if we come from a login */
  
  if (count($result == 1)){
   
    
    $password = $result[0]['password'];
    $email = $result[0]['email'];
    $name = $result[0]['username'];

    if (0 == strcmp($password,$input['password'])){
      $_SESSION['name'] = $name;
      $_SESSION['email'] = $email;
      $stmt_getid->bindParam(':email',$email,PDO::PARAM_STR);

      $result_id_name = stmtQuery($stmt_getid);

      $_SESSION['id'] = $result_id_name[0]['id'];


      $output = array( "name" => $name );
      echo json_encode($output);
      http_response_code(200);
    } else{
      $_SESSION['name'] = "Mismatch";
      $_SESSION['email'] = "";
      $name="";
      http_response_code(404);
    }

  } else{
    http_response_code(404); 
  }
}
?>