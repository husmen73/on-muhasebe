<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function add_invoice($data)
{
	$ci =& get_instance();
	
	if(!isset($data['date'])){$data['date'] = date('Y-m-d H:i:s'); }
	if(!isset($data['type'])){$data['type'] = 'invoice'; }
	$data['user_id'] = get_the_current_user('id');
	
	$ci->db->insert('invoices', $data);
	$insert_id = $ci->db->insert_id();
	
	return $insert_id;
}

function update_invoice($data)
{
	$ci=& get_instance();
	
	$ci->db->where('id', $data['id']);
	$ci->db->update('invoices', $data);
	
	if($ci->db->affected_rows() > 0)
	{
		return 1;
	}
	else
	{
		return 0;	
	}
}


function get_invoice($invoice_id)
{	
	$ci =& get_instance();
	
	$ci->db->where('id', $invoice_id);
	return $query = $ci->db->get('invoices')->row_array();
}

function get_invoice_item($item_id, $data='')
{	
	$ci =& get_instance();
	
	$ci->db->where('id', $item_id);
	if(is_array($data))
	{
		$ci->db->where($data);
	}
	$query = $ci->db->get('invoice_items')->row_array();

	if($query)
	{
		return $query;
	}
	else
	{
		return 0;
	}
}


function add_item($data)
{
	$ci =& get_instance();
	
	$invoice = get_invoice($data['invoice_id']);
	
	if(!isset($data['type'])) { $data['type'] = 'invoice'; }
	
	$data['date'] = date('Y-m-d H:i:s');
	$data['account_id'] = $invoice['account_id'];
	$data['user_id'] 	= get_the_current_user('id');
	$data['in_out']		= $invoice['in_out'];
	
	if(!isset($data['total'])){ $data['total'] = $data['quantity'] * $data['quantity_price'];}
	
	$ci->db->insert('invoice_items', $data);
	$insert_id = $ci->db->insert_id();
	
	return $insert_id;	
}

function update_invoice_item($data)
{
	$ci =& get_instance();
	
	$invoice = get_invoice($data['invoice_id']);
	
	$data['account_id'] = $invoice['account_id'];
	$data['in_out']		= $invoice['in_out'];
	
	$ci->db->where('id', $data['id']);
	$ci->db->update('invoice_items', $data);
	if($ci->db->affected_rows() >0)
	{
		return 1;
	}
	else
	{
		return 0;	
	}
}


function calc_invoice_items($invoice_id)
{
	$invoice = get_invoice($invoice_id);
	
	$ci =& get_instance();
	//total
	$ci->db->where('status', 1);
	$ci->db->where('invoice_id', $invoice_id);
	$ci->db->select_sum('total');
	$total = $ci->db->get('invoice_items')->row_array();
	//tax
	$ci->db->where('status', 1);
	$ci->db->where('invoice_id', $invoice_id);
	$ci->db->select_sum('tax');
	$tax = $ci->db->get('invoice_items')->row_array();
	//sub_total
	$ci->db->where('status', 1);
	$ci->db->where('invoice_id', $invoice_id);
	$ci->db->select_sum('sub_total');
	$sub_total = $ci->db->get('invoice_items')->row_array();
	//sub_total
	$ci->db->where('status', 1);
	$ci->db->where('invoice_id', $invoice_id);
	$ci->db->select_sum('quantity');
	$quantity = $ci->db->get('invoice_items')->row_array();
	
	$ci->db->where('id', $invoice_id);
	if($invoice['in_out'] == 0)
	{
		$ci->db->update('invoices', array(
		'total'=>'-'.$total['total'],
		'tax'=>'-'.$tax['tax'],
		'grand_total'=>'-'.$sub_total['sub_total'],
		'quantity'=>$quantity['quantity']
		));
	}
	else
	{
		$ci->db->update('invoices', array(
		'total'=>$total['total'],
		'tax'=>$tax['tax'],
		'grand_total'=>$sub_total['sub_total'],
		'quantity'=>$quantity['quantity']
		));
	}
}


function calc_account_balance($account_id)
{
	$ci =& get_instance();
	
	$ci->db->where('status', 1);
	$ci->db->where('account_id', $account_id);
	$ci->db->select_sum('grand_total');	
	$grand_total = $ci->db->get('invoices')->row_array();
	
 	$ci->db->where('id', $account_id);
	$ci->db->update('accounts', array('balance'=>$grand_total['grand_total']));
}


function get_text_in_out($in_out)
{
	if($in_out == 0)
	{
		return get_lang('input');
	}
	elseif($in_out == 1)
	{
		return get_lang('output');
	}
	else
	{
		return get_lang('Error!');
	}
}


function change_status_invoice($invoice_id, $data)
{
	$ci =& get_instance();
	$continue = true;
	
	if($data['status'] == 0){}
	else if($data['status'] == 1){}
	else{$continue = false;}
	
	if($continue == true)
	{
		$ci->db->where('id', $invoice_id);
		$ci->db->update('invoices', array('status'=>$_GET['status']));
		
		$ci->db->where('invoice_id', $invoice_id);
		$ci->db->update('invoice_items', array('status'=>$_GET['status']));
	}
	
	$ci->db->where('invoice_id', $invoice_id);
	$items = $ci->db->get('invoice_items')->result_array();
	foreach($items as $item)
	{
		calc_product($item['product_id']);
		
		$ci->db->where('id', $item['product_serial_id']);
		$ci->db->update('product_serials', array('invoice_id'=>''));
	}
}

?>