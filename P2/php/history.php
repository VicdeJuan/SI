<?php

	function getHistory($dir,$filename){

		$file = fopen($dir.$filename, "r");

		if ($file == null) {

			return 404;
		}else{
			$movies_id = simplexml_load_file($dir.$filename)->xpath('movie/id');
			
			for ($i=0; $i < count($movies_id); $i++) { 
				$movies_id[$i] = (int) $movies_id[$i];
			}

			$movies = simplexml_load_file('../data/movies.xml');
			$array = array();

			for ($j=0,$i=0; $i < count($movies); $i++) { 
				/*if ((int) $movies->movie[0]->id == $movies_id[0]) {
					return 123;
				}*/
				if (array_search((int) $movies->movie[$i]->id, $movies_id)){
					$array[$j] = $movies->movie[$i];
					$j++;
				}
			}

			fclose($file);
			return $array;
		}
	}

	function addHistory($dir,$filename){
		$file = fopen($dir.$filename, "a");

		if ($file == null) {
			return 404;
		}else{
			/**
			 * TODO: code here
			 */
		}

	}

	function createHistory($dir,$filename){
		$file = fopen($dir.$filename, "w");

		if ($file == null) {
			return 404;
		}else{
			/**
			 * TODO: code here
			 */
		}

	}


?>