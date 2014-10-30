<?php


	function getHistory($dir){

		$filename = $dir."/"."history.xml";
		$file = fopen($dir."/history.xml", "r");

		if ($file == null) {
			
			return 404;
		
		}else{

			$movies_id = simplexml_load_file($filename)->xpath('movie');			
			fclose($file);
			

			$movies = simplexml_load_file('../data/movies.xml');
			$array = array(array());

			for ($i=0; $i < count($movies_id); $i++) { 
				$index_id =  $movies_id[$i]->id;
				$index_id =  (int) $index_id;

				$array[$i]['id'] = $index_id;
				$array[$i]['quantity'] = (int) $movies_id[$i]->quantity;
			}


			return $array;
		}
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

		error_log($dir."/history.xml");

			
			$current = simplexml_load_file($dir."history.xml");			
			$currArr = (array) $current;

			foreach ($movies_id as $pair) {
				$index = _searchForId($pair['id'],$currArr['movie']);
				if ($index >= 0) {
					$node = $current->movie[$index];
					$node->quantity = $pair['quantity']+$node->quantity;
				}else{
					$child = $current->addChild('movie');
					$child->addChild('id',$pair['id']);
					$child->addChild('quantity',$pair['quantity']);					
				}
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
			fwrite($file,"<?xml version=".'"'."1.0".'"'." encoding=".'"'."UTF-8".'"'."?>\n<!DOCTYPE note SYSTEM ".'"'."/data/history.dtd".'"'.">\n<catalog>\n</catalog>\n");
			fclose($file);
			return 200;
		}

	}


?>