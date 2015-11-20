<?php
/* ----------------------------------------------
	ADD FICHE
---------------------------------------------- */
function add_fiche($type, $date, $current_id)
{
	global $database;
	
	$type				=	safety_filter($type);
	$date				=	safety_filter($date).' '.date("H:i:s");
	$current_id			=	safety_filter($current_id);
	
	if($current_id == 0)	{ alert_box('alert', get_lang('No Current ID')); return false;	}
	
	mysql_query("INSERT INTO $database->fiche
	(type, date, current_id)
	VALUES
	('$type', '$date', '$current_id')");
	if(mysql_affected_rows() > 0)
	{
		return mysql_insert_id();
	}
	else
	{
		alert_box('alert', mysql_error());
	}
}



/* ----------------------------------------------
	ADD ITEM
---------------------------------------------- */
function add_fiche_item($fiche_id, $date, $type, $current_id, $product_id, $quantity, $price)
{
	global $database;
	
	$fiche_id		= safety_filter($fiche_id);
	$date			= safety_filter($date);
	$type			= safety_filter($type);
	$current_id		= safety_filter($current_id);
	$product_id		= safety_filter($product_id);
	$quantity		= safety_filter($quantity);
	$price			= safety_filter($price);
	
	if(!is_money_format($price))	{ alert_box('alert', get_lang(''));	return false;}
	
	$total = $quantity * $price;
	$cost_price = get_product($product_id, 'cost_price');
	
	mysql_query("INSERT INTO $database->fiche_items
	(date, type, fiche_id, current_id, product_id, quantity, cost_price, price, total)
	VALUES
	('$date', '$type', '$fiche_id', '$current_id', '$product_id', '$quantity', '$cost_price', '$price', '$total')");
	if(mysql_affected_rows() > 0)
	{
		$item_id = mysql_insert_id();
		calc_fiche_total($fiche_id);
		calc_current_balance($current_id);
		calc_product_amount($product_id);
		
		return $item_id;
	}
	else
	{
		alert_box('alert', mysql_error());
		return false;
	}
}




/* ----------------------------------------------
	THE FICHE
---------------------------------------------- */
if(isset($_GET['fiche_id']) or isset($_POST['fiche_id']))
{
	if(isset($_GET['fiche_id'])) { $fiche_id = safety_filter($_GET['fiche_id']);	}
	else if(isset($_POST['fiche_id'])) {	$fiche_id = safety_filter($_POST['fiche_id']);	}	
	
	$query_fiche = mysql_query("SELECT * FROM $database->fiche WHERE id='$fiche_id'");
	while($list_fiche = mysql_fetch_assoc($query_fiche))
	{
		$fiche['id'] 			=	$list_fiche['id'];	
		$fiche['status'] 		=	$list_fiche['status'];	
		$fiche['type'] 			=	$list_fiche['type'];	
		$fiche['date'] 			=	$list_fiche['date'];	
		$fiche['current_id'] 	=	$list_fiche['current_id'];
		$fiche['total'] 		=	$list_fiche['total'];
		$fiche['tax_rate'] 		=	$list_fiche['tax_rate'];
		$fiche['tax'] 			=	$list_fiche['tax'];
		$fiche['grand_total'] 	=	$list_fiche['grand_total'];
		$fiche['fiche_items'] 	=	$list_fiche['fiche_items'];	
	}
	
	function get_the_fiche($value)
	{
		global $fiche;
		return $fiche[$value];	
	}
	
	function the_fiche($value)
	{
		echo get_the_fiche($value);
	}
}



/* ----------------------------------------------
	FICHE
---------------------------------------------- */
function get_fiche($fiche_id, $value)
{
	global $database;
	
	$fiche_id = safety_filter($fiche_id);
	
	$query_fiche = mysql_query("SELECT * FROM $database->fiche WHERE id='$fiche_id'");
	while($list_fiche = mysql_fetch_assoc($query_fiche))
	{
		return $list_fiche[$value];	
	}	
}

function fiche($fiche_id, $value)
{
	echo get_fiche($fiche_id, $value);
}



/* ----------------------------------------------
	FICHE ITEM
---------------------------------------------- */
function get_fiche_item($item_id, $value)
{
	global $database;
	
	$item_id = safety_filter($item_id);
	
	$query_fiche_item = mysql_query("SELECT * FROM $database->fiche_items WHERE id='$item_id'");
	while($list_fiche_item = mysql_fetch_assoc($query_fiche_item))
	{
		return $list_fiche_item[$value];
	}
}

function fiche_item($item_id, $value)
{
	echo get_fiche_item($item_id, $value);
}



/* ----------------------------------------------
	CALC FICHE TOTAL
---------------------------------------------- */
function calc_fiche_total($fiche_id)
{
	global $database;
	
	$fiche_id = safety_filter($fiche_id);
	
	$total = 0;
	$i = 0;
	$query_fiche_items = mysql_query("SELECT * FROM $database->fiche_items WHERE status='publish' AND fiche_id='$fiche_id'");
	while($list_fiche_items = mysql_fetch_assoc($query_fiche_items))
	{
		$i++;
		$total = $total + $list_fiche_items['total'];
	}
	
	$tax_rate = get_fiche($fiche_id, 'tax_rate');
	
	$tax = ($total / 100) * $tax_rate;
	
	$grand_total = $total + $tax;
	
	mysql_query("UPDATE $database->fiche SET total='$total', tax='$tax', grand_total='$grand_total', fiche_items='$i' WHERE id='$fiche_id'");
	echo mysql_error();
}
?>