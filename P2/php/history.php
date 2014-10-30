<?php


	function getHistory($dir){

		$filename = $dir."/"."history.xml";
		$file = fopen($dir."/history.xml", "r");

		if ($file == null) {
			
			return 404;
		
		}else{

			$movies_id = simplexml_load_file($filename)->xpath('pedido');	

			return $movies_id;		
			fclose($file);
			
			$movies = simplexml_load_file('../data/movies.xml');
			$array = array(array(array()));

			for ($j=0; $j < count($movies_id); $j++) { 
				$pedido = (array) $movies_id[$j];

				for ($i=0; $i < count($pedido); $i++) { 
					$index_id =  $pedido[$i]->id;
					$index_id =  (int) $index_id;

					$array[$j][$i]['id'] = $index_id;
					$array[$j][$i]['quantity'] = (int) $pedido[$i]->quantity;
					$array[$j][$i]['date'] = (string) $pedido[$i]->date;
				}
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

			
			$current = simplexml_load_file($dir."history.xml");	
			$currArr = (array) $current;
			
			$childAux = $current->addChild('pedido');
			foreach ($movies_id as $pair) {
				$child = $childAux->addChild('movie');
				$child->addChild('id',$pair['id']);
				$child->addChild('quantity',$pair['quantity']);	
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
			fwrite($file,"<?xml version=".'"'."1.0".'"'." encoding=".'"'."UTF-8".'"'."?>\n<!DOCTYPE note SYSTEM ".'"'."/data/history.dtd".'"'.">\n<catalog>\n</catalog>\n");
			fclose($file);
			return 200;
		}

	}


?>