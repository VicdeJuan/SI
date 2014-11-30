 <?php
 require_once dirname(__FILE__).'/common.php';
 require_once dirname(__FILE__).'/history.php';
 require_once dirname(__FILE__).'/sql.php';

/*
Campos de customers:
customerid , firstname , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname" , "firstname"
 */


$dbh = DBConnect_PDO();

$sql_userSelect = <<<SQL
SELECT email, username, password
	FROM customers
	WHERE email = :email AND password = :password;
SQL;

$sql_userCreate = <<<SQL
INSERT INTO customers (
	firstname, lastname, address1, address2, city, state, zip,
	country, region, email, phone, creditcardtype, creditcard,
	creditcardexpiration, username, password, age, income, gender)
VALUES (
	:username, 'lastname', 'address1', 'address2', 'city', 'state', 28030,
	'country', 'region', :email, 'phone', 'VISA', :creditCard,
 	:expireCard, :username , :password, 21, 0, 'M');
SQL;

$sql_getId = <<<SQL
SELECT customerid AS id, username AS name
	FROM customers WHERE email = :email;
SQL;

$stmt = $dbh->prepare($sql_userSelect);
$stmt_create = $dbh->prepare($sql_userCreate);
$stmt_getid = $dbh->prepare($sql_getId);

if (isset($_POST['email']))
{
	$input = $_POST;
}
else
{
	$json = file_get_contents('php://input');
	$input = json_decode($json,true);
}

$email = $input['email'];
$password = $input['password'];
$name = $input['name'];

$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->bindParam(':password', $password, PDO::PARAM_STR);

$stmt->execute();
$result = $stmt->fetchAll();


if (!session_start())
{
	header("Location: ".$applicationBaseDir."exit.html");
	exit();
}

if (count($result) == 0 && isset($_POST['creditCard']))        /*User does not exist so we create it.*/
{
	$stmt_create->bindParam(':username', $name,PDO::PARAM_STR);
	$stmt_create->bindParam(':email', $email,PDO::PARAM_STR);
	$stmt_create->bindParam(':creditCard',$input['creditCard'],PDO::PARAM_STR);
	$stmt_create->bindParam(':expireCard',$input['expireDate'],PDO::PARAM_STR);
	$stmt_create->bindParam(':password',$password,PDO::PARAM_STR);

	if($stmt_create->execute() === FALSE)
	{
		print_r($dbh->errorInfo());
		return;
	}

	$_SESSION['name'] = $name;
	$_SESSION['email'] = $email;
	$stmt_getid->bindParam(':email', $email, PDO::PARAM_STR);

	$result_id_name = stmtQuery($stmt_getid);

	$_SESSION['id'] = $result_id_name[0]['id'];
	$_SESSION['name'] = $result_id_name[0]['name'];

	header("Location: ".$applicationBaseDir."index.php");
}
else
{      /* Now, if we come from a login */
	if (count($result == 1))
	{
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

	}
	else
	{
		http_response_code(404);
	}
}
?>
