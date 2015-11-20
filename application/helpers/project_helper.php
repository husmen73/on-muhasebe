<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_p_location($account_id)
{
	$ci =& get_instance();
	
	$ci->db->where('id', $account_id);
	$query = $ci->db->get('p_p_locations')->row_array();
	return $query;
}

function get_p_project_card($project_id)
{
	$ci =& get_instance();
	
	$ci->db->where('id', $project_id);
	$query = $ci->db->get('p_projects')->row_array();
	return $query;
}

function get_p_project_job($job_id)
{
	$ci =& get_instance();
	
	$ci->db->where('id', $job_id);
	$query = $ci->db->get('p_project_jobs')->row_array();
	return $query;
}

function get_p_work_order($work_order_id)
{
	$ci =& get_instance();
	
	$ci->db->where('id', $work_order_id);
	$query = $ci->db->get('p_p_orders')->row_array();
	return $query;
}


function get_p_project_cards()
{
	$ci =& get_instance();
	$projects = array();
	$query = $ci->db->get('p_projects')->result_array();
	$i = 0;
	while($i < 100000)
	{
		if(!isset($query[$i]))
		{
			$i = 100000;
		}
		else
		{
			$projects[$query[$i]['id']] = $query[$i];
		}
		$i++;
	}
	
	return $projects;
}

function get_p_project_card_list_for_array()
{
	$ci =& get_instance();
	$projects = array();
	$query = $ci->db->get('p_projects')->result_array();
	$i = 0;
	while($i < 100000)
	{
		if(!isset($query[$i]))
		{
			$i = 100000;
		}
		else
		{
			$projects[$query[$i]['id']] = $query[$i];
		}
		$i++;
	}
	
	return $projects;
}


function get_p_location_card_list_for_array()
{
	$ci =& get_instance();
	$locations = array();
	$query = $ci->db->get('p_p_locations')->result_array();
	$i = 0;
	while($i < 100000)
	{
		if(!isset($query[$i]))
		{
			$i = 100000;
		}
		else
		{
			$locations[$query[$i]['id']] = $query[$i];
		}
		$i++;
	}
	
	return $locations;
}




function get_project_list($data='')
{
	$ci =& get_instance();
	
	$accounts = get_account_list_for_array(); 
	
	$ci->db->where('status', 1);
	$query = $ci->db->get('p_projects')->result_array();	
	
	?>
    <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-bordered table-condensed dataTable_noExcel_noLength_noInformation">
    	<thead>
        	<tr>
            	<th width="1"></th>
                <th><?php lang('Name'); ?></th>
                <th><?php lang('Barcode Code'); ?></th>
                <th><?php lang('Account Card'); ?></th>
                <th><?php lang('Support Status'); ?></th>
                <th><?php lang('Response Time'); ?></th>
            </tr>
        </thead>
        <tbody>
    <?php
	foreach($query as $account)
	{
	?>
    	<tr>
        	<td width="1">
            	<a href="javascript:;" class="btn btn-xs btn-default btnSelected_project" 
            		data-project_id='<?php echo $account['id']; ?>' 
                	data-project_name='<?php echo $account['name']; ?>'
                    data-project_code='<?php echo $account['code']; ?>'
                    data-project_support_status='<?php echo $account['support_status']; ?>'
                    data-project_price='<?php echo get_money($account['price']); ?>'
                    data-project_mileage_price='<?php echo get_money($account['mileage_price']); ?>'
                    >
				<?php lang('Choose'); ?></a>
            </td>
            <td><?php echo $account['name']; ?></td>
            <td><?php echo $account['code']; ?></td>
            <td><?php echo $accounts[$account['account_id']]['name']; ?></td>
            <td><?php if($account['support_status'] == 1): ?>
                	<?php lang('Support Yes'); ?>
                <?php else: ?>
                	<?php lang('Support No'); ?>
                <?php endif; ?></td>
            <td><?php echo $account['response_time']; ?></td>
        </tr>
    <?php
	}
	?>
    	</tbody>
    </table>
    
    <script>
        $('.btnSelected_project').click(function() {
			<?php if(isset($data['project_id'])): ?>$('#<?php echo $data['project_id']; ?>').val($(this).attr('data-project_id'));<?php endif; ?>
			<?php if(isset($data['project_name'])): ?>$('#<?php echo $data['project_name']; ?>').val($(this).attr('data-project_name'));<?php endif; ?>
			<?php if(isset($data['project_code'])): ?>$('#<?php echo $data['account_name']; ?>').val($(this).attr('data-project_code'));<?php endif; ?>
			<?php if(isset($data['project_support_status'])): ?>$('#<?php echo $data['project_support_status']; ?>').val($(this).attr('data-project_support_status'));<?php endif; ?>
			<?php if(isset($data['project_price'])): ?>$('#<?php echo $data['project_price']; ?>').val($(this).attr('data-project_price'));<?php endif; ?>
			<?php if(isset($data['project_mileage_price'])): ?>$('#<?php echo $data['project_mileage_price']; ?>').val($(this).attr('data-project_mileage_price'));<?php endif; ?>
			<?php if(isset($data['RUN'])): echo $data['RUN'];  endif; ?>
			$('.close').click();
		});
	</script>
    <?php
}




function get_location_list($data='')
{
	$ci =& get_instance();
	
	$accounts = get_account_list_for_array(); 
	$projects = get_p_project_cards();
	
	$ci->db->where('status', 1);
	$locations = $ci->db->get('p_p_locations')->result_array();	
	
	?>
    <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-bordered table-condensed dataTable_noExcel_noLength_noInformation">
    	<thead>
        	<tr>
            	<th width="1"></th>
                <th><?php lang('Name'); ?></th>
                <th><?php lang('Custom Code'); ?></th>
                <th><?php lang('City'); ?></th>
                <th><?php lang('County'); ?></th>
                <th><?php lang('Account Card'); ?></th>
                <th><?php lang('Project Card'); ?></th>
            </tr>
        </thead>
        <tbody>
    <?php
	foreach($locations as $location)
	{
	?>
    	<tr>
        	<td width="1">
            	<a href="javascript:;" class="btn btn-xs btn-default btnSelected_project" 
            		data-location_id='<?php echo $location['id']; ?>' 
                	data-location_name='<?php echo $location['name']; ?>'
                    data-location_project_id='<?php echo $location['project_id']; ?>'
                    >
				<?php lang('Choose'); ?></a>
            </td>
            <td><?php echo $location['name']; ?></td>
            <td><?php echo $location['custom_code']; ?></td>
            <td><?php echo $location['city']; ?></td>
            <td><?php echo $location['county']; ?></td>
            <td><?php echo @$accounts[$location['account_id']]['name']; ?></td>
            <td><?php echo @$projects[$location['project_id']]['name']; ?></td>
        </tr>
    <?php
	}
	?>
    	</tbody>
    </table>
    
    <script>
        $('.btnSelected_project').click(function() {
			<?php if(isset($data['location_id'])): ?>$('#<?php echo $data['location_id']; ?>').val($(this).attr('data-location_id'));<?php endif; ?>
			<?php if(isset($data['location_name'])): ?>$('#<?php echo $data['location_name']; ?>').val($(this).attr('data-location_name'));<?php endif; ?>
			<?php if(isset($data['RUN'])):?>
				<?php echo $data['RUN']; echo '('; ?>$(this).attr("data-location_project_id")<?php echo ');'; endif; ?>
			$('.close').click();
		});
	</script>
    <?php
}


function add_work_order_item($data)
{
	$ci =& get_instance();
	
	$work_order = get_p_work_order($data['work_order_id']);
	
	if(!isset($data['type'])) { $data['type'] = 'invoice'; }
	
	$data['date'] = date('Y-m-d H:i:s');
	$data['account_id'] = $work_order['account_id'];
	$data['location_id'] = $work_order['location_id'];
	$data['project_id'] = $work_order['project_id'];
	$data['user_id'] 	= get_the_current_user('id');
	
	$ci->db->insert('p_p_order_items', $data);
	$insert_id = $ci->db->insert_id();
	
	return $insert_id;		
}


function delete_work_order_item($data)
{
	$ci =& get_instance();
	
	$ci->db->where('id', $data['item_id']);
	$ci->db->update('p_p_order_items', array('status'=>'0'));
	
	return $ci->db->affected_rows();	
}


function calc_order_items($work_order_id)
{
	$work_order = get_p_work_order($work_order_id);
	
	$ci =& get_instance();
	//total
	$ci->db->where('status', 1);
	$ci->db->where('work_order_id', $work_order_id);
	$ci->db->select_sum('total');
	$total = $ci->db->get('p_p_order_items')->row_array();
	//tax
	$ci->db->where('status', 1);
	$ci->db->where('work_order_id', $work_order_id);
	$ci->db->select_sum('tax');
	$tax = $ci->db->get('p_p_order_items')->row_array();
	//sub_total
	$ci->db->where('status', 1);
	$ci->db->where('work_order_id', $work_order_id);
	$ci->db->select_sum('sub_total');
	$sub_total = $ci->db->get('p_p_order_items')->row_array();
	
	
	$ci->db->where('id', $work_order_id);
	$ci->db->update('p_p_orders', array(
	'total'=>$total['total'],
	'tax'=>$tax['tax'],
	'grand_total'=>$sub_total['sub_total']
	));
}


function p_order_fine($work_order_id)
{
	$ci =& get_instance();
	
	$work_order = get_p_work_order($work_order_id);
	
	if($work_order['order_status'] != 'complete' and $work_order['criminal_status'] == '1' and $work_order['custom_criminal_status'] == '0')
	{
		$work_order['hours'] = hours_left($work_order['end_date']);
		$ci->db->where('id', $work_order_id);
		$ci->db->update('p_p_orders', array('hours'=>$work_order['hours']));
		
		if($work_order['hours'] < 0)
		{
			$fine = $work_order['hourly_fines'] * str_replace('-', '', $work_order['hours']);
			$ci->db->where('id', $work_order_id);
			$ci->db->update('p_p_orders', array('fine'=>$fine));
		}
	}
	
	if($work_order['custom_criminal_status'] == '1')
	{
		$fine = '0';
		$ci->db->where('id', $work_order_id);
		$ci->db->update('p_p_orders', array('fine'=>$fine));
	}
}



?>