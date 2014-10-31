<?php
	require_once 'movies.php';

	function getHistory($dir){

		$filename = $dir."/"."history.xml";
		$file = fopen($dir."/history.xml", "r");

		if (!file_exists($filename))
			return array();

		$purchases = simplexml_load_file($filename)->xpath('pedido');	
		$movies = getAllMovies();
		$purchasesArray = array();

		foreach ($purchases as $purchase) 
		{
			$purchaseValue = array();

			foreach ($purchase->movie as $purchaseItem) 
			{
				$movieReference = findMovie($movies, $purchaseItem->id);
				$movieImage = "";
				$movieTitle = "unknown";

				if ($movieReference != null)
				{
					$movieImage = $movieReference['image'];
					$movieTitle = $movieReference['title'];
				}

				$movie = array(
					"id" => (int) $purchaseItem,
					"price" => (int) $purchaseItem->price,
					"quantity" => (int) $purchaseItem->quantity,
					"image" => $movieImage,
					"title" => $movieTitle
				);

				array_push($purchaseValue, $movie);
			}

			array_push($purchasesArray, $purchaseValue);
		}

		return $purchasesArray;
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

	function addHistory($dir,$movies_id){

			
			$current = simplexml_load_file($dir."history.xml");	
			$currArr = (array) $current;
			
			$childAux = $current->addChild('pedido');
			foreach ($movies_id as $pair) {
				$child = $childAux->addChild('movie');
				$child->addChild('id',$pair['id']);
				$child->addChild('quantity',$pair['quantity']);
				$child->addChild('price', $pair['price']);	
				$child->addChild('date',date(DATE_ATOM));			
			}


			$current->asXML($dir."/history.xml");


			return $current;

	}

	function createHistory($dir){
		$file = fopen($dir."/history.xml", "w");

		if ($file == null) {
			fclose($file);
			return 404;
		}else{
			fwrite($file,"<?xml version=".'"'."1.0".'"'." encoding=".'"'."UTF-8".'"'."?>\n<!DOCTYPE catalog SYSTEM ".'"'."/data/history.dtd".'"'.">\n<catalog>\n</catalog>\n");
			fclose($file);
			return 200;
		}

	}


?>