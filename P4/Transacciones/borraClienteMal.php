<html>
  <head>
    <link rel="stylesheet" type="text/css" href="borracliente.css">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Borra cliente</title>
  </head>
  <body>

<?php
if (isset($_GET['enviado'])){
	define("PGUSER", "alumnodb");
	define("PGPASSWORD", "alumnodb");
	define("DSN","pgsql:host=localhost;dbname=si1;options='--client_encoding=UTF8'");


	$conn = new PDO(DSN,PGUSER,PGPASSWORD);

	$conn ->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$id = $_GET['id'];

	/* 
	El dump viene sin ON DELETE CASCADE

	alter table orderdetail drop constraint orderdetail_orderid_fkey;
	alter table orders drop constraint orders_customerid_fkey;

	ALTER TABLE orderdetail ADD CONSTRAINT orderdetail_orderid_fkey FOREIGN KEY (orderid) references orders(orderid);
	ALTER TABLE orders ADD CONSTRAINT orders_customerid_fkey FOREIGN KEY (orderid) references customers(customerid);

	 */


	function PrintTable($pdo,$new,$id)
	{	
		
		$sql_print_customer = "select firstname from customers";
		$sql_print_order = "select orderid,customerid,totalamount from orders";
		$sql_print_prod = "select orderid,prod_id,price from orderdetail";
		$sql_get_orders = "select DISTINCT orderid from orderdetail join orders using(orderid) join customers using (customerid) where customerid = $id;";

		if ($new) {
			$new_conn = new PDO(DSN,PGUSER,PGPASSWORD);
			$new_conn->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}else{
			$new_conn = $pdo;
		}
		
		$sql_temp_ctm = $sql_print_customer." where customerid = $id;";
		$sql_temp_order = $sql_print_order." where customerid = $id;";

		/*Construcción consulta para ver orderdetail.*/
		$res = $new_conn->prepare($sql_get_orders);
		$res->execute();
		$_t_prod_id = $res->fetchAll();

		$sql_temp_prd = $sql_print_prod." where 1=0 ";
		foreach ($_t_prod_id as $prdid) {
			$sql_temp_prd = $sql_temp_prd." union ".$sql_print_prod."  where orderid = ".$prdid['orderid'];	
		}
		$sql_temp_prd = $sql_temp_prd.";";


		/******* Customers ***********/
		$res = $new_conn->prepare($sql_temp_ctm);
		$res->execute();
		$res = $res->fetchAll();
		echo "<h4>Customer</h4>";
		if(count($res) == 0){
			echo "<div class=\"CSSTableGenerator\"> tabla vacía (NOT FOUND) </div>";
		}else{
			echo "<div class=\"CSSTableGenerator\"><table>";
			echo "<tr><td>firstname</td></tr>";
			foreach ($res as $row) {
				echo "<tr><td>".$row['firstname']."</td></tr>";
			}
			echo "</table></div>";
		}

		/******* Orders *************/
		$res = $new_conn->prepare($sql_temp_order);
		$res->execute();
		$res = $res->fetchAll();

		echo "<h4>Orders</h4>";
		if(count($res) == 0){
			echo "<div class=\"CSSTableGenerator\"> tabla vacía (NOT FOUND) </div>";
		}else{

			echo "<div class=\"CSSTableGenerator\"><table>";
			echo "<tr><td>orderid</td><td>customerid</td><td>totalamount</td></tr>";
			foreach ($res as $row) {
				echo "<tr><td>".$row['orderid']."</td><td>".$row['customerid']."</td><td>".$row['totalamount']."</td></tr>";
			}
			echo "</table></div>";
		}

		/******* Orderdetail ********/

		$res = $new_conn->prepare($sql_temp_prd);
		$res->execute();
		$res = $res->fetchAll();

		echo "<h4>Orderdetail</h4>";
		if(count($res) == 0){
			echo "<div class=\"CSSTableGenerator\"> tabla vacía (NOT FOUND) </div>";
		}else{
			echo "<div class=\"CSSTableGenerator\"><table>";
			echo "<tr><td>orderid</td><td>prod_id</td><td>price</td></tr>";
			foreach ($res as $row) {
				echo "<tr><td>".$row['orderid']."</td><td>".$row['prod_id']."</td><td>".$row['price']."</td></tr>";
			}
			echo "</table></div>";
		}



		return;
	}

	$delete_bien = array(0 => "delete from orderdetail  where orderid in (select orderid from orders  where customerid = $id);",
					1 => "delete from orders  where customerid = $id;",
					2 => "delete from customers  where customerid = $id;" );

	$delete_mal = array(0 => "delete from orderdetail  where orderid in (select orderid from orders  where customerid = $id);",
					1 => "delete from customers  where customerid = $id;",
					2 => "delete from orders  where customerid = $id;");


	$delete_bien_pdo = array(0 => "delete from orderdetail  where orderid in (select orderid from orders  where customerid = :id);",
					1 => "delete from orders  where customerid = :id;",
					2 => "delete from customers  where customerid = :id;" );

	$delete_mal_pdo = array(0 => "delete from orderdetail  where orderid in (select orderid from orders  where customerid = :id);",
					1 => "delete from customers  where customerid = :id;",
					2 => "delete from orders  where customerid = :id;");

	if (isset($_GET['commit']))
		unset($_GET['bien']);

	if (!isset($_GET['bien'])){
		if (isset($_GET['PDO'])){
			$delete = $delete_mal_pdo;
		}else{
			$delete = $delete_mal;
		}
	}
	else{
		if (isset($_GET['PDO'])){
			$delete = $delete_bien_pdo;
		}else{
			$delete = $delete_bien;
		}
	}

	$err=0;


	if (isset($_GET['PDO'])){
		$conn->beginTransaction();
	}
	else{
		$conn->query("BEGIN;");
	}

	$customerid = $_GET['id'];

	echo "<h2>Begin tran	saction</h2>";
	PrintTable($conn,FALSE,$id);
	foreach ($delete as $del) {
		try{
			if (isset($_GET['PDO']))
			{
				$dbh = $conn->prepare($del);
				$dbh->bindParam(":id",$customerid);
				echo "<h2>A procesar: ".$del."</h2>";
				$dbh->execute();
				$dbh->fetchAll();
				if (isset($_GET['commit'])){
					$dbh->commit();			
					echo "<h3> Commit intermedio";
					$dbh->beginTransaction();	
				}
			}else{
				echo "<h2>A procesar: ".$del."</h2>";
				$res = $conn->query("$del");
				if (isset($_GET['commit'])){
					$conn->query("COMMIT;");
					echo "<h3> Commit intermedio";
					$conn->query("BEGIN;");
				}
			}
			PrintTable($conn,FALSE,$id);
		}catch (Exception $e){
			echo '<h2>Excepción capturada: ',  $e->getMessage(), "<br> Ejecutando roll-back</h2>";
			if (isset($_GET['PDO'])){
				$conn->rollBack();
			}
			else{
				$conn->query("ROLLBACK;");
			}
			echo "<h2>Estado de la tabla después del rollback</h2>";
			PrintTable($conn,FALSE,$id);
			$err = 1;
			break;
		}
	}
	if ($err != 1){
		echo "<h2>Pre-commit (nueva conexión)</h2>";
		PrintTable($conn,TRUE,$id);
		if(isset($_GET['PDO'])){
			$conn->commit();
		}
		else{
			$conn->query("COMMIT;");
		}
		echo "<h2>Post-commit;</h2>";
		PrintTable($conn,FALSE,$id);

	}

}else {
	?>
    <form action="">
		<label> id </label><input type="numeric" name="id"><br>
		<input type="checkbox" name="PDO">Utilizar PDO para la transacción.<br>
		<input type="checkbox" name="bien">Realizar borrado completo (o con error).<br>
		<input type="checkbox" name="commit">Realizar commit intermedio (implica borrado mal).<br>
		<input type="submit" name="enviado" value="Enviar">

	</form>
<?php
}

?>
</body>
</html>