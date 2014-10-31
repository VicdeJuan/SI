<?php
	require_once 'movies.php';

	function getHistory($dir){

		$filename = $dir."/"."history.xml";
		$file = fopen($dir."/history.xml", "r");

		if (!file_exists($filename))
			return array();

		$movies = getAllMovies();
		$purchases = simplexml_load_file($filename)->xpath('pedido');	
		$purchasesArray = array();

		foreach ($purchases as $purchase) 
		{
			$purchaseMovies = array();

			foreach ($purchase->movie as $purchaseItem) 
			{
				$movieId = (string) $purchaseItem->id;
				$movieReference = findMovie($movies, $movieId);
				$movieImage = "";
				$movieTitle = "unknown";

				if ($movieReference != null)
				{
					$movieImage = $movieReference['image'];
					$movieTitle = $movieReference['title'];
				}

				$movie = array(
					"id" => $movieId,
					"price" => (int) $purchaseItem->price,
					"quantity" => (int) $purchaseItem->quantity,
					"image" => $movieImage,
					"title" => $movieTitle,
				);

				array_push($purchaseMovies, $movie);
			}

			array_push($purchasesArray, array(
				"movies" => $purchaseMovies,
				"date" => (string) $purchase->date
			));
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

	function addHistory($dir,$cartItems)
	{
			$current = simplexml_load_file($dir."history.xml");	
			$currArr = (array) $current;
			
			$purchase = $current->addChild('pedido');
			
			foreach ($cartItems as $movie) 
			{
				$xmlMovie = $purchase->addChild('movie');
				$xmlMovie->addChild('id',$movie['id']);
				$xmlMovie->addChild('quantity',$movie['quantity']);
				$xmlMovie->addChild('price', $movie['price']);	
			}

			$purchase->addChild('date',date(DATE_ATOM));			


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