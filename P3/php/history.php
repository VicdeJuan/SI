<?php
	require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/php/movies.php';	
	require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/php/sql.php';	


	function getHistory(){

		$dbh = DBConnect_PDO();

		$stmt_getOrderIds = $dbh->prepare("select distinct orderid,orders.orderdate as date from orders join orderdetail using (orderid) where customerid = :customerid;" );
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
				

			$dbh = DBConnect_PDO();
			$stmt_order = $dbh->prepare("insert into orders (orderid,orderdate,customerid,tax,status) values (default,now(),:customerid,:tax,'Processed');");
			$stmt_order->bindParam(':customerid',$_SESSION['id'],PDO::PARAM_STR);
			$stmt_order->bindParam(':tax',$tax,PDO::PARAM_INT);
			$stmt_order->execute();
			
			$stmt_getOrderId = $dbh->prepare("select orderid from orders where customerid=:customerid order by orderid desc limit 1");
			$stmt_getOrderId->bindParam(':customerid',$_SESSION['id'],PDO::PARAM_STR);
			$orderid = stmtQuery($stmt_getOrderId);

			$stmt_insertDetail = $dbh->prepare("insert into orderdetail (orderid,prod_id,price,quantity) values (:orderid,:prod_id,:price,:quantity);");

			foreach ($cartItems as $movie) 
			{
				$stmt_insertDetail->bindParam(':quantity',$movie['quantity'],PDO::PARAM_INT);
				$stmt_insertDetail->bindParam(':price',$movie['price'],PDO::PARAM_INT);

				$netamount = $movie['price'] * $movie['quantity'];

				$stmt_insertDetail->bindParam(':prod_id',$cartItems['prod_id']);
				$stmt_insertDetail->bindParam(':orderid',$orderid);
				$stmt_insertDetail->execute();

			}

			$stmt_final = $dbh->prepare("update orders set netamount=:netamount and totalamount=:totalamount and status='Paid' where orderid=:orderid");
			$stmt_final->bindParam(':netamount',$netamount,PDO::PARAM_INT);
			$totalamount = $tax * $netamount;
			$stmt_final->bindParam(':totalamount',$totalamount,PDO::PARAM_INT);
			$stmt_final->bindParam('orderid',$orderid,PDO::PARAM_STR);

			$stmt_final->execute();
			return $tax;

	}


?>