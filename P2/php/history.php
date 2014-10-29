<?php

	function getMoviesFromId($array_ids){
			$movies = simplexml_load_file('../data/movies.xml');
			$array = array();

			for ($i=0; $i < count($array_ids); $i++) { 
				$array[$i] = $movies->movie[$movies_id[$i]];
			}
			return $array;
	}

	function readMoviesHistoryId($file){
			$movies_id = simplexml_load_file($file)->xpath('movie/id');			
			for ($i=0; $i < count($movies_id); $i++) { 
				$movies_id[$i] = (int) $movies_id[$i];
			}
			return $movies_id;		
	}

	function getHistory($dir,$filename){

		$file = fopen($dir.$filename, "r");

		if ($file == null) {
			
			return 404;
		
		}else{

			$movies_id = readMoviesHistoryId($dir.$filename);
			fclose($file);

			return getMoviesFromId($movies_id);
		}
	}

	function searchForId($id, $array) {
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

	function addHistory($dir,$filename,$movies_id){
		$file = fopen($dir.$filename, "a");

		if ($file == null) {
			fclose($file);
			return 400;
		}else{
			
			$current = simplexml_load_file($dir.$filename);			
			$currArr = (array) $current;
			foreach ($movies_id as $pair) {
				$index = searchForId($pair['id'],$currArr['movie']);
				if ($index >= 0) {
					$node = $current->movie[$index];
					$node->cuantity = $pair['cuantity']+$node->cuantity;
				}else{
					$child = $current->addChild('movie');
					$child->addChild('id',$pair['id']);
					$child->addChild('cuantity',$pair['cuantity']);					
				}
			}


			$current->asXML($dir.$filename);


			fclose($file);
			return $current;
		}

	}

	function createHistory($dir,$filename){
		$file = fopen($dir.$filename, "w");

		if ($file == null) {
			fclose($file);
			return 404;
		}else{
			fwrite($file,"<?xml version=".'"'."1.0".'"'." encoding=".'"'."UTF-8".'"'."?>\n<!DOCTYPE note SYSTEM ".'"'."/data/history.dtd".'"'.">\n<catalog>\n</catalog>\n");
			fclose($file);
			return 200;
		}

	}


?>