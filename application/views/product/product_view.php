<?php calc_product($product_id); ?>
<?php $product = get_product(array('id'=>$product_id)); ?>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
  <li><a href="<?php echo site_url('product'); ?>"><?php lang('Product'); ?></a></li>
  <li><a href="<?php echo site_url('product/list_product'); ?>"><?php lang('Product List'); ?></a></li>
  <li class="active"><?php echo $product['code']; ?></li>
</ol>



<?php if(isset($_GET['status']) and get_the_current_user('role') <= 3): ?>
	<?php
	if($_GET['status'] == '0') { add_log(array('type'=>'delete', 'title'=>get_lang('Product Card'), 'description'=>get_lang('Product Card has been deleted.'), 'other_id'=>'product:'.$product['id'])); }
	elseif($_GET['status'] == '1') { add_log(array('type'=>'delete', 'title'=>get_lang('Product Card'), 'description'=>get_lang('Deleted items activated the card again.'), 'other_id'=>'product:'.$product['id'])); }
	else { exit('error'); }
	
	$this->db->where('id', $product['id']);
	$this->db->update('products', array('status'=>$_GET['status']));
	$product = get_product(array('id'=>$product_id));
	?>
<?php endif; ?>


<?php if($product['status'] == '0'): ?>
	<?php alertbox('alert-danger', get_lang('Deleted Item Card.'), '', false); ?>
<?php endif; ?>


<?php
if(isset($_POST['update_product']) and is_log())
{
	$continue = true;
	$this->form_validation->set_rules('code', get_lang('Barcode Code'), 'min_length[3]|max_length[30]');
	$this->form_validation->set_rules('name', get_lang('Product Name'), 'required|min_length[3]|max_length[30]');
	$this->form_validation->set_rules('cost_price', get_lang('Cost Price'), 'numeric|max_length[10]');
	$this->form_validation->set_rules('sale_price', get_lang('Sale Price'), 'numeric|max_length[10]');
	$this->form_validation->set_rules('tax_rate', get_lang('Tax Rate'), 'integer|max_length[10]');

	if ($this->form_validation->run() == FALSE)
	{
		alertbox('alert-danger', '', validation_errors());
	}
	else
	{
		$product['code'] = $this->input->post('code');
		$product['name'] = $this->input->post('name');
		$product['description'] = $this->input->post('description');
		$product['cost_price'] = $this->input->post('cost_price');
		$product['sale_price'] = $this->input->post('sale_price');
		$product['tax_rate'] = $this->input->post('tax_rate');
		$product['serial'] = $this->input->post('serial');
		
		// Have barcode?
		$this->db->where('status', '1');
		$this->db->where_not_in('id', $product['id']);
		$this->db->where('code', $product['code']);
		$query = $this->db->get('products')->result_array();
		if($query)
		{
			alertbox('alert-danger', get_lang('This barcode is found in the database. Used for another product.'));
			$continue = false;
		}
	
		if($continue)
		{
			if(update_product($product['id'], $product))
			{
				alertbox('alert-success', get_lang('Operation is Successful'), '');	
				$log['date'] = $this->input->post('log_time');
				$log['type'] = 'product';
				$log['title']	= get_lang('Product');
				$log['description'] = get_lang('Product card has been updated.');
				$log['other_id'] = 'product:'.$product['id'];
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


<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="#product_card" data-toggle="tab"><?php lang('Product Card'); ?></a></li>
    <?php if($product['serial'] == 1): ?>
    <li><a href="#serial_box" data-toggle="tab" id="tab_serial_click"><?php lang('Serial Numbers'); ?></a></li>
    <?php endif; ?>
    <li><a href="#invoices" data-toggle="tab"><?php lang('Invoices'); ?></a></li>
    <li><a href="#history" data-toggle="tab"><?php lang('History'); ?></a></li>
    <li class="dropdown">
		<a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown"><?php lang('Options'); ?> <b class="caret"></b></a>
		<ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
			<li><a href="<?php echo site_url('user/new_message/?product_id='.$product['id']); ?>"><span class="glyphicon glyphicon-envelope mr9"></span><?php lang('New Message'); ?></a></li>
            <li><a href="<?php echo site_url('user/new_task/?product_id='.$product['id']); ?>"><span class="glyphicon glyphicon-globe mr9"></span><?php lang('New Task'); ?></a></li>
            
            <li class="divider"></li>
        	<li><a href="javascript:;" onclick="print_barcode();"><span class="glyphicon glyphicon-print mr9"></span><?php lang('Barcode Print'); ?></a></li>
            
            <?php if(get_the_current_user('role') <= 3): ?>
                <li class="divider"></li>
                <?php if($product['status'] == '1'): ?>
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




<!-- product card -->
<div class="tab-pane fade active in" id="product_card">
<div class="row">
<div class="col-md-8">


<?php $product = get_product(array('id'=>$product_id)); ?>
<form name="form_new_product" id="form_new_product" action="" method="POST" class="validation">
    <div class="row">
        <div class="col-md-8">
               
            <div class="form-group">
                <label for="code" class="control-label ff-1 fs-16"><?php lang('Barcode Code'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
                    <input type="text" id="code" name="code" class="form-control input-lg ff-1" placeholder="<?php lang('Barcode Code'); ?>" minlength="3" maxlength="30" value="<?php echo $product['code']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="control-label ff-1 fs-16"><?php lang('Product Name'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="name" name="name" class="form-control input-lg ff-1 required" placeholder="<?php lang('Product Name'); ?>" minlength="3" maxlength="30" value="<?php echo $product['name']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="control-label ff-1 fs-16"><?php lang('Description'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></span>
                    <input type="text" id="description" name="description" class="form-control input-lg ff-1" placeholder="<?php lang('Description'); ?>" value="<?php echo $product['description']; ?>">
                </div>
            </div>
    
                              
        </div> <!-- /.col-md-4 -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="cost_price" class="control-label ff-1 fs-16"><?php lang('Cost Price'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                    <input type="text" id="cost_price" name="cost_price" class="form-control input-lg ff-1 number" placeholder="0.00" value="<?php echo get_cost_price($product['cost_price']); ?>">
                </div>
            </div> <!-- /.form-group -->
            <div class="form-group">
                <label for="sale_price" class="control-label ff-1 fs-16"><?php lang('Sale Price'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                    <input type="text" id="sale_price" name="sale_price" class="form-control input-lg ff-1 number" placeholder="0.00" value="<?php echo $product['sale_price']; ?>">
                </div>
            </div> <!-- /.form-group -->
            <div class="form-group">
                <label for="tax_rate" class="control-label ff-1 fs-16"><?php lang('Tax Rate'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><strong>%</strong></span>
                    <input type="text" id="tax_rate" name="tax_rate" class="form-control input-lg ff-1 digits" value="<?php echo $product['tax_rate']; ?>">
                </div>
            </div> <!-- /.form-group -->
            
            <div class="form-group">
                <label for="serial" class="control-label ff-1 fs-16"><?php lang('Serial Numbered'); ?></label>
                <select name="serial" id="serial" class="form-control ff-1 fs-16">
                	<option value="0" <?php if($product['serial'] == '0'){echo'selected';} ?>><?php lang('Serial Number None'); ?></option>
                    <option value="1" <?php if($product['serial'] == '1'){echo'selected';} ?>><?php lang('Serial Numbered Item'); ?></option>
                </select>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md- -->
    </div> <!-- /.row -->

    <div class="text-right">
    	<?php if($product['status'] == 1): ?>
			<?php $options['who_can_edit_product_card'] = get_option(array('option_group'=>'product', 'option_key'=>'who_can_edit_product_card')); ?>
            <?php if(get_the_current_user('role') <= $options['who_can_edit_product_card']['option_value']): ?>
                <input type="hidden" name="log_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
                <input type="hidden" name="update_product" />
                <button class="btn btn-default btn-lg btn-success"><?php lang('Update'); ?> &raquo;</button>
            <?php endif; ?>
        <?php endif; ?>
    </div> <!-- /.text-right -->
</form>
</div> <!-- /.col-md-8 -->
<div class="col-md-4">
	<div class="box_title turq"><span class="glyphicon glyphicon-leaf mr9"></span><?php lang('Amount'); ?></div>
    <div class="alert alert-block <?php if($product['amount'] < 0){echo'alert-danger';}else{echo'alert-success';} ?> fade in">
    	<h4 style="font-size:40px;" class="text-center"><?php echo get_product_amount($product['amount']); ?></h4>
    </div>

	<div class="box_title turq"><span class="glyphicon glyphicon-barcode mr9"></span><?php lang('Barcode Code'); ?></div>
	<a href="javascript:;" class="img-thumbnail text-center" onclick="print_barcode();" style="width:100%;">
    	<div style="height:4px;"></div>
    	<img src="<?php echo get_barcode($product['code']); ?>" class="img-responsive" />
    </a>
    
    
    <script>
	function print_barcode() { 
		new_window = window.open("<?php echo get_print_barcode($product['code']); ?>?print", "<?php echo $product['code']; ?>","location=0,status=0,scrollbars=0,width=300,height=200"); 
		new_window.moveTo(0,0); 
	}
	</script>
    
</div> <!-- /.col-md-4 -->
</div> <!-- /.row -->


</div><!-- /#product card -->



<!-- serial -->
<div class="tab-pane fade in" id="serial_box">

<?php
if(isset($_POST['add_serial_number']))
{
	$serial['status'] = '1';
	$serial['product_id'] = $product_id;
	$serial['serial'] = $this->input->post('serial_number');
	
	$is_serial = get_product_serial_number($serial);
	if($is_serial == 0)
	{
		$serial_id = add_product_serial_number($serial);
		
		if($serial_id > 0)
		{
			alertbox('alert-success', get_lang('Data Added'));
		}
	}
	else
	{
		alertbox('alert-warning', '['.$serial['serial'].'] '.get_lang('The serial number exists in the database.'));
	}
	
	echo '<script>$("#tab_serial_click").click(); $("#serial_number").click();</script>';
}

if(isset($_GET['delete_serial']))
{
	$serial_id = $_GET['serial_id'];
	
	$this->db->where('id', $serial_id);
	$this->db->update('product_serials', array('status'=>'0'));
	if($this->db->affected_rows() > 0)
	{
		alertbox('alert-danger', get_lang('Data Deleted'));	
	}
	
	echo '<script>$("#tab_serial_click").click(); $("#serial_number").click();</script>';	
}
?>

<form name="form_add_serial" id="form_add_serial" action="" method="POST" class="validation_2">
	<div class="row">
    	<div class="col-md-6">
        	<div class="form-group">
                <label for="serial_number" class="control-label ff-1 fs-16"><?php lang('Serial Number'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
                    <input type="text" id="serial_number" name="serial_number" class="form-control input-lg ff-1 required" value="">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-6 -->
        <div class="col-md-6">
        	<label>&nbsp;</label>
        	<br />
        	<input type="hidden" name="log_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
            <input type="hidden" name="add_serial_number" />
            <button class="btn btn-default btn-lg btn-success"><?php lang('Add'); ?></button>
        </div> <!-- /.col-md-6 -->
    </div> <!-- /.row -->
</form> <!-- /.form_add_serial -->

<?php 
$this->db->where('status', 1);
$this->db->where('product_id', $product['id']);
$serials = $this->db->get('product_serials')->result_array();
?>
<table class="table table-bordered table-hover table-condensed dataTable">
	<thead>
    	<tr>
        	<th class="hide"></th>
            <th></th>
            <th><?php lang('Serial Number'); ?></th>
            <th><?php lang('Invoice ID'); ?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach($serials as $serial): ?>
	<tr>
    	<td class="hide"></td>
        <td width="1"><a href="?delete_serial&serial_id=<?php echo $serial['id']; ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a></td>
        <td><?php echo $serial['serial']; ?></td>
        <td>
			<?php if($serial['invoice_id'] > 0): ?>
            	<a href="<?php site_url('invoice/view/'.$serial['invoice_id']); ?>" target="_blank">#<?php echo $serial['invoice_id']; ?></a>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
	</tbody>
</table>
</div> <!-- /#serial -->




<!-- invoices -->
<div class="tab-pane fade in" id="invoices">
<?php $accounts = get_account_list_for_array(); ?>

<table class="table table-bordered table-hover table-condensed dataTable">
	<thead>
    	<tr>
        	<th class="hide"></th>
        	<th><?php lang('ID'); ?></th>
            <th><?php lang('Date'); ?></th>
            <th><?php lang('Type'); ?></th>
            <th><?php lang('Input'); ?>/<?php lang('Output'); ?></th>
            <th><?php lang('Account Card'); ?></th>
            <th><?php lang('Quantity'); ?></th>
            <th><?php lang('Quantity Price'); ?></th>
            <th><?php lang('Total'); ?></th>
            <th><?php lang('Tax Rate'); ?></th>
            <th><?php lang('Tax'); ?></th>
            <th><?php lang('Sub Total'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php 
	$this->db->where('status', 1);
	$this->db->where('product_id', $product['id']);
	$this->db->order_by('ID', 'ASC');
	$invoices = $this->db->get('invoice_items')->result_array();
	?>
    
    <?php foreach($invoices as $item): ?>
    	<tr class="<?php if($item['in_out'] == 0){echo'warning';}else{echo'success';} ;?>">
        	<td class="hide"></td>
        	<td><a href="<?php echo site_url('invoice/view/'.$item['invoice_id']); ?>">#<?php echo $item['invoice_id']; ?></a></td>
            <td><?php echo substr($item['date'],0,16); ?></td>
            <td><?php echo $item['type']; ?></td>
            <td><?php echo get_text_in_out($item['in_out']); ?></td>
            <td><a href="<?php echo site_url('account/get_account/'.$item['account_id']); ?>" target="_blank"><?php echo $accounts[$item['account_id']]['name']; ?></a></td>
            <td class="text-center"><?php echo $item['quantity']; ?></td>
            <td class="text-right"><?php echo get_money($item['quantity_price']); ?></td>
            <td class="text-right"><?php echo get_money($item['total']); ?></td>
            <td class="text-right">% (<?php echo $item['tax_rate']; ?>)</td>
            <td class="text-right"><?php echo get_money($item['tax']); ?></td>
            <td class="text-right"><?php echo get_money($item['sub_total']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table> <!-- /.table -->
</div> <!-- /#invoices -->






<!-- history -->
<div class="tab-pane fade in" id="history">
	<?php get_log_table(array('product_id'=>$product['id']), 'DESC'); ?>
</div> <!-- /#history -->












</div> <!-- /#myTabContent -->