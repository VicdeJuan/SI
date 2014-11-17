<?php require $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/base/header.php'; ?>

<?php 

$db_username = "alumnodb";
$db_password = "alumnodb";

$username = "'latino'";
$password = "'neva'";

$dbh = 	new PDO( "pgsql:dbname=olakase; host=localhost", $db_username, $db_password) ;

$stmt = $dbh->prepare( "SELECT username,password FROM customers " + "WHERE username  ?' AND password = ?" );

echo "username: ";
echo $username;
echo "<br>";
echo "pass: ";
echo $password;
echo "<br>dbh: ";
echo var_dump($dbh);

$stmt->bindParam(1, $username, PDO::PARAM_STR,25);
$stmt->bindParam(2, $password, PDO::PARAM_STR,25);

$stmt->execute();
$result = $stmt->fetchAll();
echo "result:";
echo var_dump($stmt);
echo "stmt:";
echo var_dump($result);

if ($result === FALSE){
	echo "tumadre bien no?";
	print_r($dbh->errorInfo());
}

foreach( $result as $row ) {
	echo var_dump($row);
	echo "<br>";
}

?>

<?php require $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/base/footer.php'; ?>
