<?php
/* ----------------------------------------------
	ADD LOG
---------------------------------------------- */
function add_log($date, $product_id, $current_id, $fiche_id, $title, $description)
{	
	global $database;
	$product_id		=	safety_filter($product_id);
	$current_id		=	safety_filter($current_id);
	$fiche_id		=	safety_filter($fiche_id);
	$title			=	safety_filter($title);
	$description	=	safety_filter($description);
	$date			=	safety_filter($date);
	$user_id 		= 	get_the_current_user('id');
	
	mysql_query("INSERT INTO $database->logs
	(date, user_id, product_id, current_id, fiche_id, title, description)
	VALUES
	('$date', '$user_id', '$product_id', '$current_id', '$fiche_id', '$title', '$description')");
	if(mysql_affected_rows() > 0)
	{
		return true;
	}
	else
	{
		echo mysql_error();
		return false;
	}
}


/* ----------------------------------------------
	GET LOG
---------------------------------------------- */
function get_log($date, $user_id, $title, $alert_box_status)
{
	global $database;
	$date			=	safety_filter($date);
	$user_id		=	safety_filter($user_id);
	$title			=	safety_filter($title);	
	
	if(mysql_num_rows(mysql_query("SELECT * FROM $database->logs WHERE date='$date' AND user_id='$user_id' AND title='$title'")) > 0)
	{
		if($alert_box_status == true)
		{
			alert_box('alert', get_lang('Worth repeating. Please Do not load the page again.'));	
		}
		return true;	
	}
	else
	{
		return false;	
	}
}
?>