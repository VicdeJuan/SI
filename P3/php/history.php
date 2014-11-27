<?php
	require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/php/movies.php';	
	require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/php/sql.php';	


	function getHistory(){

		$dbh = DBConnect_PDO();

		$stmt_getOrderIds = $dbh->prepare("select distinct orderid,orders.orderdate as date from orders join orderdetail using (orderid) where customerid = :customerid;" );
		$stmt_getOrderIds->bindParam(':customerid', $_SESSION['id'], PDO::PARAM_INT);
		
		$result = stmtQuery($stmt_getOrderIds);

		$stmt_getMovies = $dbh->prepare("select movieid as id,movietitle as title,url_to_img as image,orderdetail.price,quantity from orderdetail join products using (prod_id) join imdb_movies using(movieid)  where orderid = :orderid;");


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
			$stmt_order = $dbh->prepare("insert into orders (orderid,orderdate,customerid,tax,status) values (default,now(),:customerid,:tax,'Paid');");
			$stmt_order->bindParam(':customerid',$_SESSION['id'],PDO::PARAM_STR);
			$stmt_order->bindParam(':tax',$tax,PDO::PARAM_INT);
			$stmt_order->execute();
			
			$stmt_getOrderId = $dbh->prepare("select orderid from orders where customerid=:customerid order by orderid desc limit 1");
			$stmt_getOrderId->bindParam(':customerid',$_SESSION['id'],PDO::PARAM_STR);
			$orderid = stmtQuery($stmt_getOrderId);

			$stmt_getProdId = $dbh->prepare("select prod_id from products where movieid=:movieid;");
			$stmt_insertDetail = $dbh->prepare("insert into orderdetail (orderid,prod_id,price,quantity) values (:orderid,:prod_id,:price,:quantity);");

			foreach ($cartItems as $movie) 
			{
				$stmt_insertDetail->bindParam(':quantity',$movie['quantity'],PDO::PARAM_INT);
				$stmt_insertDetail->bindParam(':price',$movie['price'],PDO::PARAM_INT);

				$stmt_getProdId->bindParam(':movieid',$movie['id'],PDO::PARAM_STR);
				$prodId = stmtQuery($stmt_getProdId);

				$netamount = $movie['price'] * $movie['quantity'];

				$stmt_insertDetail->bindParam(':prod_id',$prodId);
				$stmt_insertDetail->bindParam(':orderid',$orderid);
				$stmt_insertDetail->execute();

			}

			$stmt_insertDetail->bindParam(':netamount',$netamount,PDO::PARAM_INT);
			$totalamount = $tax * $netamount;
			$stmt_insertDetail->bindParam(':totalamount',$totalamount,PDO::PARAM_INT);

			return $tax;

	}

	function createHistory($dir){
		/*$file = fopen($dir."/history.xml", "w");

		if ($file == null) {
			fclose($file);
			return 404;
		}else{
			fwrite($file,"<?xml version=".'"'."1.0".'"'." encoding=".'"'."UTF-8".'"'."?>\n<!DOCTYPE catalog SYSTEM ".'"'."/data/history.dtd".'"'.">\n<catalog>\n</catalog>\n");
			fclose($file);
			return 200;
		}
		*/
		/* Crear historial */ 
		return 200;
	}


?>