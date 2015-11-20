<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_car($car_id)
{
	$ci =& get_instance();
	
	$ci->db->where('id', $car_id);
	return $query = $ci->db->get('p_cars')->row_array();
}


function add_car_item($array)
{
	$ci =& get_instance();
	$ci->db->insert('p_car_items', $array);
	return $ci->db->insert_id();
}

function get_car_item($item_id)
{
	$ci =& get_instance();
	
	$ci->db->where('id', $item_id);
	return $query = $ci->db->get('p_car_items')->row_array();
}


?>