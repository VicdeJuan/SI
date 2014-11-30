<?php
require_once dirname(__FILE__).'/movies.php';
require_once dirname(__FILE__).'/sql.php';


function getHistory(){

	$dbh = DBConnect_PDO();

	$stmt_getOrderIds = $dbh->prepare("select distinct orderid,orders.orderdate as date from orders join orderdetail using (orderid) where customerid = :customerid order by date desc;" );
	$stmt_getOrderIds->bindParam(':customerid', $_SESSION['id'], PDO::PARAM_INT);

	$result = stmtQuery($stmt_getOrderIds);

	$stmt_getMovies = $dbh->prepare("
		SELECT movieid AS id,movietitle AS title,url_to_img AS image,orderdetail.price,quantity
		FROM orderdetail
			JOIN products USING (prod_id)
			JOIN imdb_movies USING(movieid)
		WHERE orderid = :orderid;");


	if (count($result) == 0) {
		return array();
	}

	$history = array();



	foreach ($result as $order) {
		$purchasesArray = array();
		$stmt_getMovies->bindParam(':orderid', $order['orderid'], PDO::PARAM_STR);

		$moviesPurchased = stmtQuery($stmt_getMovies);

		array_push($history, array(
			"movies" => $moviesPurchased,
			"date" => (string) $order['date']
		));
	}

	return $history;
}

function _searchForId($id, $array) {
	$i = -1;
   foreach ($array as $val) {
       $i++;
       $val = (array) $val;

       if ($val['id'] == $id) {
           return $i;
       }
   }
   return -1;
}

function addHistory($dir,$cartItems)
{
	$tax = 15;

	$sql_order = <<<SQL
		INSERT INTO orders (orderid, orderdate, customerid, tax, status)
		VALUES (default, now(), :customerid, :tax, 'Created');
SQL;

	$sql_getOrderId = <<<SQL
		SELECT orderid FROM orders
			WHERE customerid = :customerid
			ORDER BY orderid DESC
			LIMIT 1;
SQL;

	$sql_insertDetail = <<<SQL
		INSERT INTO orderdetail (orderid,prod_id,price,quantity)
		VALUES (:orderid,:prod_id,:price,:quantity);
SQL;

	$dbh = DBConnect_PDO();
	$stmt_order = $dbh->prepare($sql_order);
	$stmt_order->bindParam(':customerid',$_SESSION['id'],PDO::PARAM_STR);
	$stmt_order->bindParam(':tax',$tax,PDO::PARAM_INT);
	$stmt_order->execute();

	$stmt_getOrderId = $dbh->prepare($sql_getOrderId);
	$stmt_getOrderId->bindParam(':customerid',$_SESSION['id'],PDO::PARAM_STR);
	$orderid = stmtQuery($stmt_getOrderId);
	$orderid = $orderid[0][0];

	$stmt_insertDetail = $dbh->prepare($sql_insertDetail);

	$netamount = 0;

	foreach ($cartItems as $movie)
	{
		$stmt_insertDetail->bindParam(':quantity',$movie['quantity'],PDO::PARAM_INT);
		$stmt_insertDetail->bindParam(':price',$movie['price'],PDO::PARAM_INT);

		$netamount += $movie['price'] * $movie['quantity'];

		$stmt_insertDetail->bindParam(':prod_id',$movie['prod_id']);
		$stmt_insertDetail->bindParam(':orderid',$orderid);
		$stmt_insertDetail->execute();
	}

	$stmt_final = $dbh->prepare("update orders set netamount=:netamount, totalamount=:totalamount, status='Paid' where orderid=:orderid");
	$stmt_final->bindParam(':netamount',$netamount,PDO::PARAM_INT);
	$totalamount = $tax * $netamount;
	$stmt_final->bindParam(':totalamount',$totalamount,PDO::PARAM_INT);
	$stmt_final->bindParam(':orderid', $orderid,PDO::PARAM_STR);

	$stmt_final->execute();
	return $tax;

}


?>
