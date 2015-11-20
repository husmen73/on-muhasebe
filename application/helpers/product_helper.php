<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function is_product_code($code)
{
	$ci =& get_instance();
	$ci->db->where('status', '1');
	$ci->db->where('code', $code);
	$query = $ci->db->get('products')->row_array();
	
	if($query)
	{
		return $query['id'];
	}
	else
	{
		return false;	
	}
}


function add_product($data)
{
	$ci =& get_instance();
	$data['tax'] = $data['sale_price'] - ($data['sale_price'] / ('1.'.$data['tax_rate']));
	
	
	$data['price'] = $data['sale_price'];
	$data['sale_price'] = $data['sale_price'] - $data['tax'];
	
	// Have barcode?
		$ci->db->where('code', $data['code']);
		$ci->db->where('status', '1');
		$query = $ci->db->get('products')->result_array();
		
		if($query)
		{
			return 'There barcode code';
			return false;
		}
	
	
	// add product card
	$ci->db->insert('products', $data);
	return $ci->db->insert_id();
}




function update_product($id, $data)
{
	$ci =& get_instance();
	
	$data['tax'] = $data['sale_price'] * '0.'.$data['tax_rate'];
	
	$ci->db->where('id', $id);
	$ci->db->update('products', $data);
	
	return true;
}






function get_product($data)
{
	$ci =& get_instance();
	
	if(is_array($data)){$ci->db->where($data);}
	else{$ci->db->where('id', $data);}
	return $query = $ci->db->get('products')->row_array();
}


function get_cost_price($cost_price)
{
	$options['who_can_see_cost_price'] = get_option(array('option_group'=>'product', 'option_key'=>'who_can_see_cost_price'));
	if(get_the_current_user('role') > $options['who_can_see_cost_price']['option_value'])
	{
		return get_money('0');
	}
	else
	{
		return get_money($cost_price);	
	}
}



function calc_product($product_id)
{
	$ci =& get_instance();
	
	$ci->db->where('product_id', $product_id);
	$ci->db->where('status', 1);
	$items = $ci->db->get('invoice_items')->result_array();
	
	$i = 0;
	$amount=0;
	while($i < 1000000000)
	{
		if(isset($items[$i]))
		{
			$item = $items[$i];
			if($item['in_out'] == '1')
			{
				$amount = $amount - $item['quantity'];	
				//echo '-'.$amount;
			}
			else
			{
				$amount = $amount + $item['quantity'];
				//echo '+'.$amount;
			}
		}
		else
		{
			$i=1000000000;	
		}
		$i++;
	}
	
	$ci->db->where('id', $product_id);
	$ci->db->update('products', array('amount'=>$amount));
}


function get_product_amount($amount)
{
	return number_format($amount, 0);	
}



function get_product_list($data='')
{
	$ci =& get_instance();
	
	$ci->db->where('status', 1);
	$query = $ci->db->get('products')->result_array();	
	
	?>
    
    
    <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-bordered table-condensed dataTable_noExcel_noLength_noInformation">
    	<thead>
        	<tr>
            	<th width="1"></th>
                <th><?php lang('Barcode Code'); ?></th>
                <th><?php lang('Product Name'); ?></th>
                <th><?php lang('Cost Price'); ?></th>
                <th><?php lang('Sale Price'); ?></th>
                <th><?php lang('Tax'); ?></th>
                <th><?php lang('Price'); ?></th>
                <th><?php lang('Amount'); ?></th>
            </tr>
        </thead>
        <tbody>
    <?php
	foreach($query as $product)
	{
	?>
    	<tr>
        	<td width="1">
            	<a href="javascript:;" class="btn btn-xs btn-default btnSelected" 
            		data-product_id='<?php echo $product['id']; ?>' 
                	data-product_code='<?php echo $product['code']; ?>'
                    data-product_name='<?php echo $product['name']; ?>'
                    data-product_cost_price='<?php echo $product['cost_price']; ?>'
                    data-product_sale_price='<?php echo $product['sale_price']; ?>'
                    data-product_tax_rate='<?php echo $product['tax_rate']; ?>'
                    data-product_tax='<?php echo $product['tax']; ?>'
                    data-product_price='<?php echo $product['price']; ?>'
                    data-product_amount='<?php echo $product['amount']; ?>'
                    data-serial='<?php echo $product['serial']; ?>'
                    >
				<?php lang('Choose'); ?></a>
            </td>
            <td><?php echo $product['code']; ?></td>
            <td><?php echo $product['name']; ?></td>
            <td class="text-right"><?php echo get_cost_price($product['cost_price']); ?></td>
            <td class="text-right"><?php echo $product['sale_price']; ?></td>
            <td class="text-center"><?php if($product['tax_rate'] > 0) : ?><small>%(<?php echo $product['tax_rate']; ?>)</small> <?php echo $product['tax']; ?><?php endif; ?></td>
            <td class="text-right"><?php echo $product['price']; ?></td>
            <td class="text-center"><?php echo get_product_amount($product['amount']); ?></td>
        </tr>
    <?php
	}
	?>
    	</tbody>
    </table>
    
    <script>
        $('.btnSelected').click(function() {
			<?php if(isset($data['product_id'])): ?>$('#<?php echo $data['product_id']; ?>').val($(this).attr('data-product_id'));<?php endif; ?>
			<?php if(isset($data['product_code'])): ?>$('#<?php echo $data['product_code']; ?>').val($(this).attr('data-product_code'));<?php endif; ?>
			<?php if(isset($data['product_name'])): ?>$('#<?php echo $data['product_name']; ?>').val($(this).attr('data-product_name'));<?php endif; ?>
			<?php if(isset($data['product_cost_price'])): ?>$('#<?php echo $data['product_cost_price']; ?>').val($(this).attr('data-product_cost_price'));<?php endif; ?>
			<?php if(isset($data['product_sale_price'])): ?>$('#<?php echo $data['product_sale_price']; ?>').val($(this).attr('data-product_sale_price'));<?php endif; ?>
			<?php if(isset($data['product_tax_rate'])): ?>$('#<?php echo $data['product_tax_rate']; ?>').val($(this).attr('data-product_tax_rate'));<?php endif; ?>
			<?php if(isset($data['product_tax'])): ?>$('#<?php echo $data['product_tax']; ?>').val($(this).attr('data-product_tax'));<?php endif; ?>
			<?php if(isset($data['product_price'])): ?>$('#<?php echo $data['product_price']; ?>').val($(this).attr('data-product_price'));<?php endif; ?>
			<?php if(isset($data['product_amount'])): ?>$('#<?php echo $data['product_amount']; ?>').val($(this).attr('data-product_amount'));<?php endif; ?>
			<!-- RUN -->
			<?php if(isset($data['RUN'])): ?> <?php echo $data['RUN']; ?> <?php endif; ?>
			$('.close').click();
			
			if($(this).attr('data-serial') == 1)
			{
				$('#serial_box').show();
			}
			else
			{
				$('#serial_box').hide();
			}
		});
	</script>
    <?php
}




function add_product_serial_number($array)
{
	$ci =& get_instance();
	$query = get_product_serial_number($array);
	if($query)
	{
		return 0;
	}
	else
	{
		$ci->db->insert('product_serials',$array);
		return $query = $ci->db->insert_id();
	}
	
}


function get_product_serial_number($array)
{
	$ci =& get_instance();
	$ci->db->where($array);
	$query = $ci->db->get('product_serials')->row_array();
	if($query)
	{	
		return $query;
	}
	else
	{
		return 0;
	}
}



?>