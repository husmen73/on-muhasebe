<?php calc_account_balance($account_id); ?>
<?php $account = get_account(array('id'=>$account_id)); ?>


<ol class="breadcrumb">
  <li><a href="<?php echo site_url(); ?>"><?php lang('Dashboard'); ?></a></li>
  <li><a href="<?php echo site_url('account'); ?>"><?php lang('Account'); ?></a></li>
  <li><a href="<?php echo site_url('account/list_account'); ?>"><?php lang('Account List'); ?></a></li>
  <li class="active"><?php echo $account['code']; ?></li>
</ol>

<?php
if(isset($_GET['status']))
{
	$log['type'] = 'account';
	$log['title']	= get_lang('Account');
	if($_GET['status'] == 0) {$log['description'] = get_lang('Account card has been deleted.');}
	else if($_GET['status'] == 1) {$log['description'] = get_lang('Account card is active again.');}
	else {exit('error!');}
	$log['other_id'] = 'account:'.$account_id;
	add_log($log);
	
	$this->db->where('id', $account['id']);
	$this->db->update('accounts', array('status'=>$_GET['status']));
}
?>



<?php $account = get_account(array('id'=>$account_id)); ?>

<?php if($account['status'] == 0): ?>
	<?php alertbox('alert-warning', get_lang('Deleted Account Card.')); ?>
<?php endif; ?>


<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="#account_card" data-toggle="tab"><?php lang('Transactions'); ?></a></li>
    <li class=""><a href="#invoices" data-toggle="tab"><?php lang('Invoices'); ?></a></li>
    <li class=""><a href="#history" data-toggle="tab"><?php lang('History'); ?></a></li>
    <li class="dropdown">
		<a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown"><?php lang('Options'); ?> <b class="caret"></b></a>
		<ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
			<li><a href="<?php echo site_url('user/new_message/?account_id='.$account['id']); ?>"><span class="glyphicon glyphicon-envelope mr9"></span><?php lang('New Message'); ?></a></li>
            <li><a href="<?php echo site_url('user/new_task/?account_id='.$account['id']); ?>"><span class="glyphicon glyphicon-globe mr9"></span><?php lang('New Task'); ?></a></li>
            
            <li class="divider"></li>
        	<li><a href="javascript:;" onclick="print_barcode();"><span class="glyphicon glyphicon-print mr9"></span><?php lang('Barcode Print'); ?></a></li>
            <li><a href="javascript:;" onclick="address_print();"><span class="glyphicon glyphicon-print mr9"></span><?php lang('Address Print'); ?></a></li>
            
            <?php if(get_the_current_user('role') <= 3): ?>
                <li class="divider"></li>
                <?php if($account['status'] == '1'): ?>
                    <li><a href="?status=0"><span class="glyphicon glyphicon-remove mr9"></span><?php lang('Delete'); ?></a></li>
                <?php else: ?>
                    <li><a href="?status=1"><span class="glyphicon glyphicon-remove mr9"></span><?php lang('Activate'); ?></a></li>
                <?php endif; ?>
            <?php endif; ?>
      </ul>
    </li>
</ul>
<div class="h20"></div>



<div id="myTabContent" class="tab-content">




<!-- account_card -->
<div class="tab-pane fade active in" id="account_card">
<div class="row">
<div class="col-md-8">

<?php
$account = get_account(array('id'=>$account_id));


if(isset($_POST['update']) and is_log())
{
	$continue = true;
	$this->form_validation->set_rules('code', get_lang('Account Code'), 'min_length[3]|max_length[30]');
	$this->form_validation->set_rules('name', get_lang('Account Name'), 'required|min_length[3]|max_length[30]');
	$this->form_validation->set_rules('balance', get_lang('Balance'), 'numeric|max_length[10]');
	$this->form_validation->set_rules('phone', get_lang('Phone'), 'integer|max_length[20]');
	$this->form_validation->set_rules('gsm', get_lang('Gsm'), 'integer|max_length[20]');
	$this->form_validation->set_rules('email', get_lang('E-mail'), 'email|max_length[50]');

	if ($this->form_validation->run() == FALSE)
	{
		alertbox('alert-danger', '', validation_errors());
	}
	else
	{
		$account['code'] = replace_text_for_utf8($this->input->post('code'));
		$account['name'] = $this->input->post('name');
		$account['name_surname'] = $this->input->post('name_surname');
		$account['balance'] = $account['balance'];
		$account['phone'] = $this->input->post('phone');
		$account['gsm'] = $this->input->post('gsm');
		$account['email'] = $this->input->post('email');
		$account['address'] = $this->input->post('address');
		$account['county'] = $this->input->post('county');
		$account['city'] = $this->input->post('city');
		$account['description'] = $this->input->post('description');
		
		// if the barcode is empty, auto-create
		if($account['code'] == '')
		{ 
			$account['code'] = replace_text_for_utf8($this->input->post('name')); 
			
			// Have barcode?
			for($i = is_account_code($account['code']); $i > 0; $i++)
			{
				$account['code'] = replace_text_for_utf8($this->input->post('name')).'-'.$i; 
				$i = is_account_code($account['code'], $account_id);
			}
		}
		else
		{
			// Have barcode?
			if(is_account_code($account['code'], $account_id))
			{
				alertbox('alert-danger', get_lang('This barcode is found in the database.'));
				$continue = false;
			}
		}
		
	
		if($continue)
		{
			if(update_account($account['id'], $account))
			{
				alertbox('alert-success', get_lang('Operation is Successful'), '');	
				$log['date'] = $this->input->post('log_time');
				$log['type'] = 'account';
				$log['title']	= get_lang('Account');
				$log['description'] = get_lang('Account card has been updated.');
				$log['account_id'] = $account_id;
				add_log($log);
			}
			else
			{
				alertbox('alert-danger', get_lang('Error!'));
			}
		}
	
		
		
		
	}
}
?>


<form name="form_new_product" id="form_new_product" action="" method="POST" class="validation">
    <div class="row">
        <div class="col-md-6">
               
            <div class="form-group">
                <label for="code" class="control-label ff-1 fs-16"><?php lang('Account Code'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
                    <input type="text" id="code" name="code" class="form-control ff-1" placeholder="<?php lang('Barcode Code'); ?>" minlength="3" maxlength="30" value="<?php echo $account['code']; ?>">
                </div>
                
            </div>
            <div class="form-group">
                <label for="name" class="control-label ff-1 fs-16"><?php lang('Account Name'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="name" name="name" class="form-control ff-1 required" placeholder="<?php lang('Account Name'); ?>" minlength="3" maxlength="30" value="<?php echo $account['name']; ?>">
                </div>
            </div>     
        </div> <!-- /.col-md-6 -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="name_surname" class="control-label ff-1 fs-16"><?php lang('Name and Surname'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="name_surname" name="name_surname" class="form-control ff-1" placeholder="<?php lang('Name Surname'); ?>" value="<?php echo $account['name_surname']; ?>" minlengt="3" maxlength="30">
                </div>
            </div> <!-- /.form-group -->
            <div class="form-group">
                <label for="balance" class="control-label ff-1 fs-16"><?php lang('Balance'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                    <input type="text" id="balance" name="balance" class="form-control ff-1 number" placeholder="0.00" value="<?php echo get_account_balance($account['balance']); ?>" readonly="readonly">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-6 -->
    </div> <!-- /.row -->
    
    <hr />
    
    <div class="row">
    	<div class="col-md-4">
        	<div class="form-group">
                <label for="phone" class="control-label ff-1 fs-16"><?php lang('Phone'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></span>
                    <input type="text" id="phone" name="phone" class="form-control ff-1 digits" minlength="7" maxlength="16" value="<?php echo $account['phone']; ?>">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-4 -->
        <div class="col-md-4">
        	<div class="form-group">
                <label for="gsm" class="control-label ff-1 fs-16"><?php lang('Gsm'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></span>
                    <input type="text" id="gsm" name="gsm" class="form-control ff-1 digits" minlength="7" maxlength="16" value="<?php echo $account['gsm']; ?>">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-4 -->
        <div class="col-md-4">
        	<div class="form-group">
                <label for="email" class="control-label ff-1 fs-16"><?php lang('E-mail'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                    <input type="text" id="email" name="email" class="form-control ff-1 email" minlength="6" maxlength="50" value="<?php echo $account['email']; ?>">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-4 -->
    </div> <!-- /.row -->
    
    
    <div class="row">
    	<div class="col-md-8">
        	<div class="form-group">
                <label for="address" class="control-label ff-1 fs-16"><?php lang('Address'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                   <textarea class="form-control" name="address" id="address" style="height:107px;" minlength="3" maxlength="250"><?php echo $account['address']; ?></textarea>
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- col-md-8 -->
        <div class="col-md-4">
        	<div class="form-group">
                <label for="county" class="control-label ff-1 fs-16"><?php lang('County'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="county" name="county" class="form-control ff-1" minlength="2" maxlength="20" value="<?php echo $account['county']; ?>">
                </div>
            </div> <!-- /.form-group -->
            <div class="form-group">
                <label for="city" class="control-label ff-1 fs-16"><?php lang('City'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="city" name="city" class="form-control ff-1" minlength="2" maxlength="20" value="<?php echo $account['city']; ?>">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-4 -->
    </div> <!-- /.row -->
    
    <div class="form-group">
        <label for="description" class="control-label ff-1 fs-16"><?php lang('Description'); ?></label>
        <div class="input-prepend input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
           <textarea class="form-control" name="description" id="description" maxlength="500"><?php echo $account['description']; ?></textarea>
        </div>
    </div> <!-- /.form-group -->

    
    <?php if($account['status'] == 1): ?>
    	<?php $options['who_can_edit_account_card'] = get_option(array('option_group'=>'account', 'option_key'=>'who_can_edit_account_card')); ?>
        
		<?php if(get_the_current_user('role') <= $options['who_can_edit_account_card']['option_value']): ?>
        <div class="text-right">
            <input type="hidden" name="log_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
            <input type="hidden" name="update" />
            <button class="btn btn-default btn-lg btn-success"><?php lang('Update'); ?> &raquo;</button>
        </div> <!-- /.text-right -->
        <?php endif; ?>
    <?php endif; ?>
</form>
</div> <!-- /.col-md-8 -->
<div class="col-md-4">

	<div class="box_title turq"><span class="glyphicon glyphicon-leaf mr9"></span><?php lang('Balance'); ?></div>
    <div class="alert alert-block <?php if($account['balance'] < 0){echo'alert-danger';}else{echo'alert-success';} ?> fade in">
    	<h4 style="font-size:40px;" class="text-center"><?php echo get_account_balance($account['balance']); ?></h4>
    </div>
    
	<div class="box_title turq"><span class="glyphicon glyphicon-barcode mr9"></span>Barkod Kodu</div>
	<a href="javascript:;" class="img-thumbnail" onclick="print_barcode();">
    	<img src="<?php echo get_barcode($account['code']); ?>" class="img-responsive" />
    </a>
    
    
    <script>
	function print_barcode() 
	{ 
		new_window = window.open("<?php echo get_print_barcode($account['code']); ?>?print", "<?php echo $account['code']; ?>","location=0,status=0,scrollbars=0,width=300,height=200"); 
		new_window.moveTo(0,0); 
	}
	
	function address_print() 
	{ 
		new_window = window.open("<?php echo site_url('account/address_print/'.$account['id']); ?>?print", "<?php echo $account['code']; ?>","location=0,status=0,scrollbars=0,width=300,height=200"); 
		new_window.moveTo(0,0); 
	}
	</script>
</div> <!-- /.col-md-4 -->
</div> <!-- /.row -->
</div> <!-- /#account_card --> 




<!-- invoices -->
<div class="tab-pane fade in" id="invoices">

<table class="table table-bordered table-hover table-condensed dataTable">
	<thead>
    	<tr>
        	<th class="hide"></th>
        	<th><?php lang('ID'); ?></th>
            <th><?php lang('Date'); ?></th>
            <th><?php lang('Type'); ?></th>
            <th><?php lang('Input'); ?>/<?php lang('Output'); ?></th>
            <th><?php lang('Products'); ?></th>
            <th><?php lang('Grand Total'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php 
	$this->db->where('status', 1);
	$this->db->order_by('ID', 'DESC');
	$this->db->where('account_id', $account['id']);
	$invoices = $this->db->get('invoices')->result_array();
	?>
    <?php foreach($invoices as $invoice): ?>
    	<tr class="<?php if($invoice['in_out'] == 0){echo'warning';}else{echo'success';} ;?>">
        	<td class="hide"></td>
        	<td><a href="<?php echo site_url('invoice/view/'.$invoice['id']); ?>">#<?php echo $invoice['id']; ?></a></td>
            <td><?php echo substr($invoice['date'],0,16); ?></td>
            <td><?php echo $invoice['type']; ?></td>
            <td><?php echo get_text_in_out($invoice['in_out']); ?></td>
            <td class="text-center"><?php echo $invoice['quantity']; ?></td>
            <td class="text-right"><?php echo get_account_balance($invoice['grand_total']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    	<?php
		$this->db->where('status', 1);
		$this->db->order_by('ID', 'DESC');
		$this->db->where('account_id', $account['id']);
		$this->db->select_sum('grand_total');
		$grand_total = $this->db->get('invoices')->row_array();
		?>
        <tr>
        	<th colspan="5"></th>
            <th class="text-right fs-16 text-danger"><?php echo get_account_balance($grand_total['grand_total']); ?></th>
        </tr>
    </tfoot>
</table> <!-- /.table -->
</div> <!-- /#invoices -->






<!-- history -->
<div class="tab-pane fade in" id="history">
	<?php get_log_table(array('account_id'=>$account['id']), 'DESC'); ?>
</div> <!-- /#history -->








<div class="h20"></div>