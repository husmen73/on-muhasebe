<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function is_account_code($code, $account_id='')
{
	$ci =& get_instance();
	
	// Have barcode?
	$ci->db->where('status', '1');
	$ci->db->where('code', $code);
	if($account_id > 0) {$ci->db->where_not_in('id', $account_id);}
	$query = $ci->db->get('accounts')->row_array();
	if($query)
	{
		return $query['id'];
	}
	else
	{
		return false;	
	}
}



function add_account($data)
{
	$ci =& get_instance();
	
	$ci->db->insert('accounts', $data);
	return $account_id = $ci->db->insert_id();	
}


function update_account($id, $data)
{
	$ci =& get_instance();
	
	$ci->db->where('id', $id);
	$ci->db->update('accounts', $data);
	
	if($ci->db->affected_rows() > 0)
	{
		return 1;
	}
	else
	{
		return 0;	
	}
}

function get_account($data)
{	
	$ci =& get_instance();
	
	if(is_array($data)) { $ci->db->where($data); }else { $ci->db->where('id', $data); }
	return $query = $ci->db->get('accounts')->row_array();
}


function get_account_balance($balance)
{
	$options['who_can_see_account_balance'] = get_option(array('option_group'=>'account', 'option_key'=>'who_can_see_account_balance'));
	
	if(get_the_current_user('role') > $options['who_can_see_account_balance']['option_value'])
	{
		return get_money(0);		
	}
	else
	{
		return get_money($balance);	
	}
}


function get_account_list($data='')
{
	$ci =& get_instance();
	
	$ci->db->where('status', 1);
	$query = $ci->db->get('accounts')->result_array();	
	
	?>
    <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-bordered table-condensed dataTable_noExcel_noLength_noInformation">
    	<thead>
        	<tr>
            	<th width="1"></th>
                <th><?php lang('Account Code'); ?></th>
                <th><?php lang('Account Name'); ?></th>
                <th><?php lang('City'); ?></th>
                <th><?php lang('Gsm'); ?></th>
                <th><?php lang('Balance'); ?></th>
            </tr>
        </thead>
        <tbody>
    <?php
	foreach($query as $account)
	{
	?>
    	<tr>
        	<td width="1">
            	<a href="javascript:;" class="btn btn-xs btn-default btnSelected" 
            		data-account_id='<?php echo $account['id']; ?>' 
                	data-account_code='<?php echo $account['name']; ?>'
                    data-account_name='<?php echo $account['name']; ?>'
                    data-account_name_surname='<?php echo $account['name_surname']; ?>'
                    data-account_balance='<?php echo number_format($account['balance'],2); ?>'
                    data-account_phone='<?php echo $account['phone']; ?>'
                    data-account_gsm='<?php echo $account['gsm']; ?>'
                    data-account_email='<?php echo $account['email']; ?>'
                    data-account_address='<?php echo $account['address']; ?>'
                    data-account_city='<?php echo $account['city']; ?>'
                    data-account_county='<?php echo $account['county']; ?>'
                    >
				<?php lang('Choose'); ?></a>
            </td>
            <td><?php echo $account['code']; ?></td>
            <td><?php echo $account['name']; ?></td>
            <td><?php echo $account['city']; ?></td>
            <td><?php echo $account['gsm']; ?></td>
            <td class="text-right"><?php echo get_account_balance($account['balance']); ?></td>
        </tr>
    <?php
	}
	?>
    	</tbody>
    </table>
    
    <script>
        $('.btnSelected').click(function() {
			<?php if(isset($data['account_id'])): ?>$('#<?php echo $data['account_id']; ?>').val($(this).attr('data-account_id'));<?php endif; ?>
			<?php if(isset($data['account_code'])): ?>$('#<?php echo $data['account_code']; ?>').val($(this).attr('data-account_code'));<?php endif; ?>
			<?php if(isset($data['account_name'])): ?>$('#<?php echo $data['account_name']; ?>').val($(this).attr('data-account_name'));<?php endif; ?>
			<?php if(isset($data['account_name_surname'])): ?>$('#<?php echo $data['account_name_surname']; ?>').val($(this).attr('data-account_name_surname'));<?php endif; ?>
			<?php if(isset($data['account_balance'])): ?>$('#<?php echo $data['account_balance']; ?>').val($(this).attr('data-account_balance'));<?php endif; ?>
			<?php if(isset($data['account_phone'])): ?>$('#<?php echo $data['account_phone']; ?>').val($(this).attr('data-account_phone'));<?php endif; ?>
			<?php if(isset($data['account_gsm'])): ?>$('#<?php echo $data['account_gsm']; ?>').val($(this).attr('data-account_gsm'));<?php endif; ?>
			<?php if(isset($data['account_email'])): ?>$('#<?php echo $data['account_email']; ?>').val($(this).attr('data-account_email'));<?php endif; ?>
			<?php if(isset($data['account_address'])): ?>$('#<?php echo $data['account_address']; ?>').val($(this).attr('data-account_address'));<?php endif; ?>
			<?php if(isset($data['account_city'])): ?>$('#<?php echo $data['account_city']; ?>').val($(this).attr('data-account_city'));<?php endif; ?>
			<?php if(isset($data['account_county'])): ?>$('#<?php echo $data['account_county']; ?>').val($(this).attr('data-account_county'));<?php endif; ?>
			<?php if(isset($data['RUN'])): echo $data['RUN'];  endif; ?>
			$('.close').click();
		});
	</script>
    <?php
}




function get_account_list_for_array()
{
	$ci =& get_instance();
	$accounts_query = $ci->db->get('accounts')->result_array();
	$i = 0;
	while($i < 100000)
	{
		if(!isset($accounts_query[$i]))
		{
			$i = 100000;
		}
		else
		{
			$accounts[$accounts_query[$i]['id']] = $accounts_query[$i];
		}
		$i++;
	}
	
	return @$accounts;
}

?>