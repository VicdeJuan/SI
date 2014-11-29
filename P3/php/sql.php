<?php
		function DBConnect_PDO(){
		$db_username = "alumnodb";
		$db_password = "alumnodb";
		$db_name = "si1";

		$dbh =  new PDO( "pgsql:dbname=$db_name; host=localhost", $db_username, $db_password) ;

		return $dbh;
		}

		function DBEndConnection_PDO(){

		}


		function stmtQuery($stmt){
			$stmt->execute();
			return $stmt->fetchAll();
		}

?>
