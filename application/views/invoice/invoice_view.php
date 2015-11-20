<?php $invoice = get_invoice($invoice_id); ?>
<?php $account = get_account($invoice['account_id']); ?>

<?php
if($invoice['type'] == 'payment')
{
	redirect(site_url('payment/view/'.$invoice['id']));	
}
?>

<ol class="breadcrumb">
	<li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
	<li><a href="<?php echo site_url('invoice'); ?>"><?php lang('Buying-Selling'); ?></a></li>
    <li><a href="<?php echo site_url('invoice/invoice_list'); ?>"><?php lang('Invoice List'); ?></a></li>
	<li class="active">
    
    <?php if($invoice['in_out'] == 0): ?>
    	<?php lang('Buying Receipt'); ?>
	<?php else: ?>
   		<?php lang('Sales Receipt'); ?>
	<?php endif; ?>
    
    #<?php echo $invoice['id']; ?></li>
</ol>

<?php 
if(isset($_GET['status'])) 
{ 
	change_status_invoice($invoice_id, array('status'=>$_GET['status'])); 
	
	$data['type'] = 'invoice';
	$data['invoice_id'] = $invoice['id'];
	$data['account_id'] = $invoice['account_id'];
	$data['title'] = get_lang('Invoice');
	if($_GET['status'] == 0){$data['description'] = get_lang('Deleted Invoice.');}else{$data['description'] = get_lang('Activated bill again.');}
	add_log($data);
	$invoice = get_invoice($invoice_id);
} 
?>



<?php calc_invoice_items($invoice['id']); ?>

<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="#transactions" data-toggle="tab"><?php lang('Transactions'); ?></a></li>
    <li class=""><a href="#history" data-toggle="tab"><?php lang('History'); ?></a></li>
    <li class="dropdown">
		<a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown"><?php lang('Options'); ?> <b class="caret"></b></a>
		<ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
			<li><a href="<?php echo site_url('user/new_message/?invoice_id='.$invoice['id']); ?>"><span class="glyphicon glyphicon-envelope mr9"></span><?php lang('New Message'); ?></a></li>
            <li><a href="<?php echo site_url('user/new_task/?invoice_id='.$invoice['id']); ?>"><span class="glyphicon glyphicon-globe mr9"></span><?php lang('New Task'); ?></a></li>
            
            <li class="divider"></li>
        	<li><a href="javascript:;" onclick="print_barcode();"><span class="glyphicon glyphicon-print mr9"></span><?php lang('Print Invoice'); ?></a></li>
            
            <?php if(get_the_current_user('role') <= 3): ?>
                <li class="divider"></li>
                <?php if($invoice['status'] == '1'): ?>
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



<div class="tab-pane fade active in" id="transactions">
<div class="row">
<div class="col-md-12">


<?php if($invoice['status'] == 0): ?>
	<?php alertbox('alert-danger', get_lang('Deleted Invoice.'), '', false); ?>
<?php endif; ?>


<form name="form_new_product" id="form_new_product" action="" method="POST" class="validation">
<div class="bs-callout bs-callout-info invoice-collout">
      			
    <div class="row">
    	<div class="col-md-4">
            <div class="form-group">
                <div class="input-prepend input-group pointer">
                    <span class="input-group-addon"><label for="date" class="pointer"><span class="glyphicon glyphicon-calendar"></span></label></span>
                    <input type="text" id="date" name="date" class="form-control input-lg ff-1 required datepicker pointer" placeholder="<?php lang('Start Date'); ?>" minlength="3" maxlength="50" value="<?php echo substr($invoice['date'],0,10); ?>" readonly>
                </div>
            </div> <!-- /.form-group -->
    	</div> <!-- /.col-md-4 -->
        <div class="col-md-4">
            <div class="form-group openModal-account_list pointer">
                <div class="input-prepend input-group">
                    <span class="input-group-addon pointer"><label for="account_name" class="pointer"><span class="glyphicon glyphicon-stop"></span></label></span>
                    <input type="text" id="account_name" name="account_name" class="form-control input-lg ff-1 pointer required" minlength="3" maxlength="30" value="<?php echo $account['name']; ?>" readonly>
                </div>
            </div> <!-- /.form-group -->
    	</div> <!-- /.col-md-4 -->
        <div class="col-md-4">
            <div class="form-group">
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><label for="description" class="pointer"><span class="glyphicon glyphicon-text-width"></span></label></span>
                    <input type="text" id="description" name="description" class="form-control input-lg ff-1 fs-12" minlength="3" maxlength="50" value="<?php echo $invoice['description']; ?>">
                </div>
            </div> <!-- /.form-group -->
    	</div> <!-- /.col-md-4 -->
    </div> <!-- /.row -->
</div> <!-- /.bs-callout -->
</form>

<div class="h20"></div>




<!-- Button trigger modal -->
<a data-toggle="modal" href="#myModal" id="modal-product_list"></a>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:800px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><?php lang('Account List'); ?></h4>
        </div>
        <div class="modal-body">
            <?php get_product_list(array(
            'product_code'=>'code',
            'product_sale_price'=>'quantity_price',
            'product_tax_rate'=>'tax_rate',
            'RUN'=>'calc();'
            )); ?>
        </div>
        
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php
if(isset($_POST['item']) and is_log())
{
	$continue = true;
	$this->form_validation->set_rules('code', get_lang('Barcode Code'), 'required');
	$this->form_validation->set_rules('amount', get_lang('Amount'), 'required|digits');
	$this->form_validation->set_rules('quantity_price', get_lang('Quantity Price'), 'number');
	$this->form_validation->set_rules('tax_rate', get_lang('Tax Rate'), 'digits');
	
	if($this->form_validation->run() == FALSE)
	{
		alertbox('alert-danger', '', validation_errors());
	}
	else
	{
		$item['invoice_id'] = $invoice['id'];
		
		$product['code'] 	= $this->input->post('code');
		$item['quantity'] = $this->input->post('amount');
		$item['quantity_price'] = $this->input->post('quantity_price');
		$item['total'] = '';
		$item['tax_rate'] 	= $this->input->post('tax_rate');
		
		$product = get_product(array('status'=>'1', 'code'=>$product['code']));
		
		if(!$product) { alertbox('alert-danger', get_lang('Barcode Code Unknown.')); }
		else
		{
			$item['product_id'] = $product['id'];
			if($item['quantity'] < 1)				{ $item['quantity'] = 1; }
			if($item['quantity_price'] == '')	{ $item['quantity_price'] = $product['sale_price']; }
			if($item['tax_rate'] == '')			{ $item['tax_rate'] = $product['tax_rate']; }
			
			$item['total'] 		= $item['quantity'] * $item['quantity_price'];
			$item['tax']		= $item['total'] / 100 * $item['tax_rate'];
			$item['sub_total'] 	= ($item['total'] + $item['tax']);
			$item['in_out'] 	= $invoice['in_out'];
			
			$item_id = add_item($item);
			if($item_id > 0)
			{
				$data['type'] = 'invoice';
				$data['invoice_id'] = $invoice['id'];
				$data['product_id'] = $product['id'];
				$data['account_id'] = $invoice['account_id'];
				$data['title'] = get_lang('Product Added');
				if($invoice['in_out'] == 0){$data['description'] = get_lang('Product purchase.');}else{$data['description'] = get_lang('Product sales.');}
				add_log($data);
				alertbox('alert-success', get_lang('Add a successful product.'));
				calc_invoice_items($invoice['id']);	
				
				
				// serial control
				if($product['serial'] == 1)
				{
					$serial['serial'] = $this->input->post('serial');
					
					if($serial > 0)
					{
						$is_serial = get_product_serial_number(array('status'=>'1', 'product_id'=>$product['id'], 'serial'=>$serial['serial']));
						
						if($is_serial > 0)
						{
							$this->db->where('id', $is_serial['id']);
							$this->db->update('product_serials', array('invoice_id'=>$invoice_id));
							
							// update item serial number
							$this->db->where('id', $item_id);
							$this->db->update('invoice_items', array(
							'product_serial_id'=>$is_serial['id']
							));
						}
						else
						{
							add_product_serial_number(array(
							'status'=>1,
							'product_id'=>$product['id'],
							'serial'=>$serial['serial'],
							'invoice_id'=>$invoice['id']
							));	
							
							// update item serial number
							$this->db->where('id', $item_id);
							$this->db->update('invoice_items', array(
							'product_serial_id'=>$is_serial['id']
							));
						}
					}
				}
			}
		}
	}
}


// delete item
if(isset($_GET['delete_item']))
{
	$item = get_invoice_item($_GET['item_id']);
	if($item['product_id'] > 0){ $product = get_product($item['product_id']);} else {$product['id'] = '0';}
	
	$this->db->where('id', $_GET['item_id']);
	$this->db->update('invoice_items', array('status'=>'0'));
	if($this->db->affected_rows() > 0)
	{
		$data['type']='item'; $data['invoice_id']=$invoice['id'];$data['product_id']=$product['id'];$data['account_id']=$invoice['account_id'];
		$data['title'] 		= get_lang('Deletion');
		$data['description']	= get_lang('Product deletion.');
		add_log($data);
		
		alertbox('alert-danger', get_lang('Product movement has been deleted.').' ['.$product['code'].']');
		
		
		// is serial number has ben active
		if($product['serial'] == 1)
		{
			if($item['product_serial_id'] > 0)
			{
				$this->db->where('id', $item['product_serial_id']);
				$this->db->update('product_serials', array(
				'invoice_id'=>''
				));
			}
		}
	}
}
?>

<form name="form_item" id="form_item" action="<?php echo site_url('invoice/view/'.$invoice['id']); ?>" method="POST" class="validation_2">
<div class="bs-callout bs-callout-danger invoice-collout">
<div class="row space-5">
	<div class="col-md-5">
    	<div class="form-group">
            <div class="input-prepend input-group">
            	<a href="javascript:;" style="position:absolute; margin-top:-20px;" class="openModal-product_list"><?php lang('product list'); ?></a>
                <span class="input-group-addon pointer openModal-product_list"><span class="glyphicon glyphicon-barcode fs-28"></span></span>
                <input type="text" id="code" name="code" class="form-control input-lg ff-1 invoice_input barcodeCode required" placeholder="<?php lang('Barcode Code'); ?>" maxlength="50" value="" style="height:74px;">
            </div>
        </div>
        <div id="serial_box">
            <label for="serial"><?php lang('Serial Number'); ?></label>
            <input type="text" name="serial" id="serial" class="form-control ff-1" />
        </div> <!-- /.serial_nox -->
    </div> <!-- /.col-md-6 -->
    <div class="col-md-1">
        <div class="form-group">
            <label for="amount" class="control-label ff-1 fs-14"><?php lang('Amount'); ?></label>
            <div class="input-prepend input-group">
                <input type="text" id="amount" name="amount" class="form-control input-lg ff-1 invoice_input fs-18" placeholder="0" maxlength="11" value="1" onkeyup="calc();" />
            </div>
        </div> <!-- /.form-group -->
    </div> <!-- /.col-md-1 -->
    <div class="col-md-1">
    	<div class="form-group">
            <label for="quantity_price" class="control-label ff-1 fs-14"><?php lang('Q. Price'); ?></label>
            <input type="text" id="quantity_price" name="quantity_price" class="form-control input-lg ff-1 invoice_input fs-14 text-right" placeholder="0.00" value="" style="padding:5px;" onkeyup="calc();">
        </div> <!-- /.form-group -->
    </div> <!-- /.col-md-2 -->
     <div class="col-md-1">
    	<div class="form-group">
            <label for="total" class="control-label ff-1 fs-14"><?php lang('Total'); ?></label>
            <input type="text" id="total" name="total" class="form-control input-lg ff-1 invoice_input fs-14 text-right" placeholder="0.00" value="" style="padding:5px;" readonly="readonly">
        </div> <!-- /.form-group -->
    </div> <!-- /.col-md-2 -->
    <div class="col-md-1">
     	<label for="tax_rate" class="control-label ff-1 fs-14 pull-left"><?php lang('Tax'); ?> (%)</label>
        <div class="clearfix"></div>
    	<div class="form-group">
            <input type="text" id="tax_rate" name="tax_rate" class="form-control input-lg ff-1 invoice_input fs-14 text-center" placeholder="0" value="" style="padding:5px; height:25px;" onkeyup="calc();">

            <input type="text" id="tax" name="tax" class="form-control input-lg ff-1 invoice_input fs-14 text-right" placeholder="0.00" value="" style="padding:5px; height:25px;" readonly="readonly">
        </div> <!-- /.form-group -->
    </div> <!-- /.col-md-1 -->
    <div class="col-md-2">
    	<div class="form-group">
            <label for="sub_total" class="control-label ff-1 fs-16"><?php lang('Sub Total'); ?></label>
            <input type="text" id="sub_total" name="sub_total" class="form-control input-lg ff-1 invoice_input fs-18 text-right" placeholder="0.00" value="" onblur="calc_subtotal();" >
        </div>
    </div> <!-- /.col-md-1 -->
    <div class="col-md-1">
    	<label>&nbsp;</label>
        <input type="hidden" name="log_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
        <input type="hidden" name="item" value="" />
    	<button class="btn btn-lg btn-success"><?php lang('Add'); ?></button>
    </div> <!-- /.col-md-1 -->
</div> <!-- /.row -->
</div> <!-- /.bs-callout -->
</form>





<!-- items -->
<?php calc_invoice_items($invoice['id']); ?>
<div class="h20"></div>
<?php
$this->db->where('status', 1);
$this->db->where('invoice_id', $invoice_id);
$items = $this->db->get('invoice_items')->result_array();

$invoice = get_invoice($invoice_id);

?>

<table class="table table-hover table-bordered table-condensed">
	<thead>
    	<tr>
        	<th width="1"></th>
        	<th><?php lang('Barcode Code'); ?></th>
            <th><?php lang('Product Name'); ?></th>
            <th><?php lang('Quantity'); ?></th>
            <th><?php lang('Quantity Price'); ?></th>
            <th><?php lang('Total'); ?></th>
            <th><?php lang('Tax Rate'); ?></th>
            <th><?php lang('Tax'); ?></th>
            <th><?php lang('Sub Total'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $item): ?>
    	<?php
		calc_product($item['product_id']);
		
		if($item['product_id'] > 0)
		{
			$product = get_product($item['product_id']);
		}
		else
		{
			$product['id'] = '';
			$product['code'] = $item['product_code'];	
			$product['name'] = $item['product_name'];	
		}
		// is serial number
		$item_serial['serial'] = '';
		if($item['product_serial_id'] > 0)
		{
			$this->db->where('id', $item['product_serial_id']);
			$item_serial = $this->db->get('product_serials')->row_array();	
		}
		?>
    	<tr>
        	<td>
            	<!-- Single button -->
                <div class="btn-group">
                  <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
                    <?php lang('Actions'); ?> <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url('invoice/view/'.$invoice['id'].'/?item_id='.$item['id'].'&delete_item=✓'); ?>"><span class="glyphicon glyphicon-remove mr9"></span><?php lang('Delete'); ?></a></li>
                  </ul>
                </div>
            </td>
        	<td>
            	<?php if($product['id'] > 0): ?>
                	<a href="<?php echo site_url('product/get_product/'.$product['id']); ?>" target="_blank"><?php echo $product['code']; ?><?php if($item_serial['serial']):?> [<?php echo $item_serial['serial']; ?>]<?php endif; ?></a>
            	<?php else: ?>
                	<?php echo $product['name']; ?>
                <?php endif; ?>
            </td>
            <td>
            	<?php if($product['id'] > 0): ?>
            		<a href="<?php echo site_url('product/get_product/'.$product['id']); ?>" target="_blank"><?php echo $product['name']; ?></a>
            	<?php else: ?>
                	<?php echo $product['name']; ?>
                <?php endif; ?>
            </td>
            <td class="text-center"><?php echo $item['quantity']; ?></td>
            <td class="text-right"><?php echo get_money($item['quantity_price']); ?></td>
            <td class="text-right"><?php echo get_money($item['total']); ?></td>
            <td class="text-center">% (<?php echo $item['tax_rate']; ?>)</td>
            <td class="text-right"><?php echo get_money($item['tax']); ?></td>
            <td class="text-right"><?php echo get_money($item['sub_total']); ?></td>
        </tr>	
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    	<tr class="fs-14 no-strong">
        	<th colspan="3" class="text-center no-strong"><?php lang('Grand Total'); ?></th>
            <th class="text-center no-strong text-danger"><?php echo $invoice['quantity']; ?></th>
            <th></th>
            <th colspan="1" class="text-right no-strong text-danger"><?php echo get_money($invoice['total']); ?></th>
            <th colspan="2" class="text-center no-strong text-danger"><?php echo get_money($invoice['tax']); ?></th>
            <th class="text-right fs-16 no-strong text-danger"><?php echo get_money($invoice['grand_total']); ?></th>
        </tr>
    </tfoot>
</table>
<!-- /items -->



</div> <!-- /.col-md-12 -->
</div> <!-- /.row -->
</div> <!-- /#transactions -->





<div class="tab-pane fade in" id="history">
	<?php get_log_table(array('invoice_id'=>$invoice['id']), 'ASC'); ?>
</div> <!-- #history -->





</div> <!-- /#myTabContent -->



<style>
.barcodeCode:focus { border:1px solid #f00 !important; }
</style>

<script>
$('.openModal-product_list').click(function() {
	$('#modal-product_list').click();
});

$('.barcodeCode').focus();
$('#serial_box').hide();

function calc()
{
	var amount 		= $('#amount').val();	
	var quantity_price = $('#quantity_price').val();	
	var total 		= $('#total').val();
	var tax_rate 	= $('#tax_rate').val();
	var tax 		= $('#tax').val();
	var sub_total 	= $('#sub_total').val();
	
	$('#total').val(parseFloat(amount * quantity_price).toFixed(2));
	
	tax = parseFloat($('#total').val() / 100);
	tax_rate = $('#tax_rate').val();
	if(tax_rate == ''){tax_rate = 0;} 
	$('#tax').val(parseFloat(parseFloat(tax) * parseInt(tax_rate)).toFixed(2));
	$('#sub_total').val(parseFloat(parseFloat($('#total').val()) + parseFloat($('#tax').val())).toFixed(2));
	$('#sub_total').val(parseFloat($('#sub_total').val()).toFixed(2));
}


function calc_subtotal()
{
	var amount 		= $('#amount').val();	
	var quantity_price = $('#quantity_price').val();	
	var total 		= $('#total').val();
	var tax_rate 	= $('#tax_rate').val();
	var tax 		= $('#tax').val();
	var sub_total 	= $('#sub_total').val();
	
	
	total = parseFloat(parseFloat(sub_total) / parseFloat('1.'+tax_rate)).toFixed(2);
	
	$('#total').val(parseFloat(total).toFixed(2));
	$('#quantity_price').val(parseFloat(parseFloat($('#total').val()) / parseFloat($('#amount').val())).toFixed(2)).toFixed(2);
	
	$('#sub_total').val(parseFloat($('#sub_total').val()).toFixed(2));s
}

</script>

<?php calc_account_balance($invoice['account_id']); ?>