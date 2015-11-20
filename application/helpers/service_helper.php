<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_service($id)
{
	$ci =& get_instance();
	
	$ci->db->where('id', $id);
	$query = $ci->db->get('tec_services')->row_array();
	return $query;
}

function add_service($data)
{
	$ci =& get_instance();
	
	$ci->db->insert('tec_services', $data);
	return $ci->db->insert_id();
}

function update_service($servie_id, $data)
{
	$ci =& get_instance();
	
	$ci->db->where('id', $servie_id);
	$ci->db->update('tec_services', $data);
	if($ci->db->affected_rows() > 0)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

function get_tec_types($array, $order_by='')
{
	$ci =& get_instance();
	
	$ci->db->where($array);
	if($order_by != ''){ $ci->db->order_by('name', $order_by); }
	return $ci->db->get('tec_type')->result_array();
}


function get_tec_type_for_array()
{
	$ci =& get_instance();
	$tec_types = $ci->db->get('tec_type')->result_array();
	$i = 0;
	while($i < 100000)
	{
		if(!isset($tec_types[$i]))
		{
			$i = 100000;
		}
		else
		{
			$return[$tec_types[$i]['id']] = $tec_types[$i];
		}
		$i++;
	}
	
	$return[0]['name'] = get_lang('OTHER');
	$return[-1]['name'] = get_lang('COMPLETED');
	
	return $return;
}




function calc_sercice_status($type_id)
{
	$ci =& get_instance();
	$ci->db->where('status', 1);
	if($type_id == -2)
	{
		$ci->db->where('service_status_id', -1);
		$ci->db->where('delivery', 1);
	}
	else
	{
		$ci->db->where('delivery', 0);
		$ci->db->where('service_status_id', $type_id);
	}
	return $query = $ci->db->get('tec_services')->num_rows(); 	
}



function calc_service_items($service_id)
{
	$service = get_service($service_id);
	
	$ci =& get_instance();
	//total
	$ci->db->where('status', 1);
	$ci->db->where('service_id', $service_id);
	$ci->db->select_sum('total');
	$total = $ci->db->get('tec_service_items')->row_array();
	//tax
	$ci->db->where('status', 1);
	$ci->db->where('service_id', $service_id);
	$ci->db->select_sum('tax');
	$tax = $ci->db->get('tec_service_items')->row_array();
	//sub_total
	$ci->db->where('status', 1);
	$ci->db->where('service_id', $service_id);
	$ci->db->select_sum('sub_total');
	$sub_total = $ci->db->get('tec_service_items')->row_array();
	
	$sub_total['sub_total'] = $service['workmanship'] + $sub_total['sub_total'];
	
	
	// cost
	$ci->db->where('status', '1');
	$ci->db->select_sum('cost_price');
	$ci->db->where('service_id', $service_id);
	$cost = $ci->db->get('tec_service_items')->row_array();
	$cost = $cost['cost_price'] + $service['expenses'];
	
	$ci->db->where('id', $service_id);
	$ci->db->update('tec_services', array(
	'total'=>$total['total'],
	'tax'=>$tax['tax'],
	'grand_total'=>$sub_total['sub_total'],
	'balance'=>($sub_total['sub_total'] - $service['payment']),
	'cost'=>$cost,
	'profit'=>($sub_total['sub_total'] - $cost)
	));
	
	
}


function delete_service_item($data)
{
	$ci =& get_instance();
	
	$ci->db->where('id', $data['item_id']);
	$ci->db->update('tec_service_items', array('status'=>'0'));
	
	return $ci->db->affected_rows();	
}


function get_service_item($data)
{
	$ci =& get_instance();
	$ci->db->where($data);
	$query = $ci->db->get('tec_service_items')->row_array();
	return $query;
}


function calc_warranty_date($date)
{
	$date1 = strtotime(date('Y-m-d'));
	$date2 = strtotime($date); 
	$day = (($date2-$date1)/3600)/24 ; 
	return str_replace('', '', round($day)); 
}


function service_send_mail($email)
{
	
}


?>