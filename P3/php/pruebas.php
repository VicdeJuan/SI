<?php require $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/base/header.php'; ?>

<?php 

require $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/php/history.php';

/*$db_username = "alumnodb";
$db_password = "alumnodb";

$username = "latino";
$password = "neva";

$dbh = 	new PDO( "pgsql:dbname=olakase; host=localhost", $db_username, $db_password) ;

$stmt = $dbh->prepare( "SELECT username,password FROM customers WHERE username = :username AND password = :password" );

echo "username: ";
echo $username;
echo "<br>";
echo "pass: ";
echo $password;
echo "<br>dbh: ";
echo var_dump($dbh);
echo "result:";
echo var_dump($stmt);
echo "stmt:";
echo var_dump($result);

if ($result === FALSE){
	echo "tumadre bien no?";
	print_r($dbh->errorInfo());
}
echo $result[0]['username']; 
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
foreach( $result as $row ) {
  echo var_dump($row['username']);
  echo "<br>";
}
$stmt->bindParam(':password', $password, PDO::PARAM_STR);

$stmt->execute();
$result = $stmt->fetchAll();
*/

session_start();

$dbh =  DBConnect_PDO();
$stmt = $dbh->prepare( "SELECT customerid as id, username as name  FROM customers WHERE email = :email" );
$email = 'pup.nosh@kran.com';
$stmt->bindParam(':email',$email,PDO::PARAM_STR);

$res = stmtQuery($stmt);

echo $res[0]['id'];

?>

<?php require $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/base/footer.php'; ?>
