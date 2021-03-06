<?php

session_start();

if(!isset($_SESSION['cart']))
	save_cart(array());

function save_cart($cart)
{
	$_SESSION['cart'] = json_encode($cart);
}

function get_cart()
{
	return json_decode($_SESSION['cart'], true);
}

function get()
{
	echo json_encode(get_cart());
}

function post()
{
	$cart = get_cart();
	$json = file_get_contents('php://input');
	$toAdd = json_decode($json, true);
    $toAdd = $toAdd['item'];
	$found = false;

	foreach ($cart as &$item) {
		if($item['id'] === $toAdd['id'])
		{
			$item['quantity'] += 1;
			$found = true;
			break;
		}
	}

	if(!$found)
	{
		$toAdd['quantity'] = 1;
		array_push($cart, $toAdd);
	}

	save_cart($cart);
}

function remove_from_array($array, $item)
{

}

function delete()
{
	if(isset($_GET['all']))
	{
		save_cart(array());
		return;
	}

	$cart = get_cart();
	$itemId = $_GET['itemId'];
	$found = null;

	foreach ($cart as &$item) {
		if($item['id'] == $itemId)
		{
			$found = &$item;
			break;
		}
	}

	if(!is_null($found))
	{
		$item['quantity'] -= 1;

		if($item['quantity'] <= 0)
		{
			if (($key = array_search($item, $cart)) !== false)
				unset($cart[$key]);
		}

		echo var_dump($cart);
		save_cart($cart);
	}
	else
	{
		http_response_code(404);
	}
}

switch($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		if(isset($_GET['delete']))
			delete();
		else
			get();
		break;
	case 'POST':
		post();
		break;
	case 'DELETE':
		delete();
		break;
	default:
		http_response_code(501);
};

?>
