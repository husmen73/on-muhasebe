<?php $invoice = get_invoice($invoice_id); ?>
<?php $account = get_account($invoice['account_id']); ?>

<ol class="breadcrumb">
	<li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
	<li><a href="<?php echo site_url('payment'); ?>"><?php lang('Payment'); ?></a></li>
    <li><a href="<?php echo site_url('payment/payment_list'); ?>"><?php lang('Payment List'); ?></a></li>
	<li class="active">
		<?php if($invoice['in_out'] == 0): ?>
            <?php lang('Get Money'); ?>
        <?php else: ?>
            <?php lang('Give Money'); ?>
        <?php endif; ?>
        
        #<?php echo $invoice['id']; ?>
    </li>
</ol>



<?php 
if(isset($_GET['status'])) 
{ 
	change_status_invoice($invoice_id, array('status'=>$_GET['status'])); 
	
	$data['type'] = 'invoice';
	$data['invoice_id'] = $invoice['id'];
	$data['account_id'] = $invoice['account_id'];
	$data['title'] = get_lang('Invoice');
	if($_GET['status'] == 0){$data['description'] = get_lang('Deleted Payment.');}else{$data['description'] = get_lang('Activated bill again.');}
	add_log($data);
	$invoice = get_invoice($invoice_id);
} 
?>

<?php if($invoice['status'] == 0): ?>
	<?php alertbox('alert-danger', get_lang('Deleted Payment.'), '', false); ?>
<?php endif; ?>


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
<div class="col-md-8">




<form name="form_new_product" id="form_new_product" action="" method="POST" class="validation">
	
    <div class="row">
    	<div class="col-md-6">
            <div class="form-group">
                <label for="date" class="control-label ff-1 fs-16"><?php lang('Payment Date'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" id="date" name="date" class="form-control input-lg ff-1 required datepicker pointer" placeholder="<?php lang('Start Date'); ?>" minlength="3" maxlength="50" value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>
            </div> <!-- /.form-group -->
    	</div> <!-- /.col-md-6 -->
        <div class="col-md-6">
            <div class="form-group openModal-account_list">
            	<input type="hidden" name="account_id" id="account_id" value="<?php $invoice['account_id']; ?>" />
                <label for="account_name" class="control-label ff-1 fs-16"><?php lang('Account Card'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon pointer"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="account_name" name="account_name" class="form-control input-lg ff-1 pointer required" placeholder="<?php lang('Select Account'); ?>" minlength="3" maxlength="30" value="<?php echo $account['name']; ?>" readonly>
                </div>
            </div> <!-- /.form-group -->
    	</div> <!-- /.col-md-6 -->
    </div> <!-- /.row -->
    
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="payment" class="control-label ff-1 fs-16"><?php lang('Payment'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                    <input type="text" id="payment" name="payment" class="form-control input-lg ff-1 required number" placeholder="0.00" maxlength="10" value="<?php echo get_money($invoice['grand_total']); ?>" onkeypress="calc_payment();" onkeyup="calc_payment();">
                </div>
            </div> <!-- /.form-group -->
    	</div> <!-- /.col-md-6 -->
        <div class="col-md-6">
            
    	</div> <!-- /.col-md-6 -->
    </div> <!-- /.row -->

    <div class="row">
    	<div class="col-md-4">
        	<div class="form-group">
                <label for="payment_type" class="control-label ff-1 fs-16"><?php lang('Payment Type'); ?></label>
                <select name="payment_type" id="payment_type" class="form-control ff-1 input-lg">
                	<?php if($invoice['val_1'] == 'cash'): ?>
                    	<option value="cash"><?php lang('Cash'); ?></option>
                    <?php else: ?>
                    	<option value="checks"><?php lang('Checks'); ?></option>
                    <?php endif; ?>
                </select>
                                
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-4 -->
        <div class="col-md-8">
       		<div id="payment_type_checks_avtice">
            	<div class="row">
                	<div class="col-md-6">
                        <div class="form-group">
                            <label for="fall_due_on" class="control-label ff-1 fs-16"><?php lang('Fall Due On'); ?></label>
                            <div class="input-prepend input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                <input type="text" id="fall_due_on" name="fall_due_on" class="form-control input-lg ff-1 required datepicker pointer" placeholder="<?php lang('Fall Due On'); ?>" minlength="3" maxlength="50" value="<?php echo $invoice['val_2']; ?>" readonly>
                            </div>
                        </div> <!-- /.form-group -->
                    </div> <!-- /.col-md-6 -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="checks_serial_no" class="control-label ff-1 fs-16"><?php lang('Serial No'); ?></label>
                            <div class="input-prepend input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                                <input type="text" id="checks_serial_no" name="checks_serial_no" class="form-control input-lg ff-1" placeholder="<?php lang('Serial No'); ?>" value="<?php echo $invoice['val_3']; ?>">
                            </div>
                        </div> <!-- /.form-group --> 
                    </div> <!-- /.col-md-6 -->
                </div> <!-- /.row -->
                
            </div> <!-- /#payment_type_checks_avtice --> 
            <script> $('#payment_type_checks_avtice').hide(); </script>
			<?php if($invoice['val_1'] == 'checks'): ?>
            <script>
                $('#payment_type_checks_avtice').show('blonde');
            </script>
            <?php endif; ?>
            <div class="form-group">
                <label for="description" class="control-label ff-1 fs-16"><?php lang('Description'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="description" name="description" class="form-control input-lg ff-1" placeholder="<?php lang('Description'); ?>" minlength="3" maxlength="50" value="<?php echo $invoice['description']; ?>">
                </div>
            </div> <!-- /.form-group --> 
        </div> <!-- /.col-md-8 -->
    </div> <!-- /.row -->
   
</form>
</div> <!-- /.col-md-8 -->
<div class="col-md-4">
	<span class="help-block note">
        <h4><span class="glyphicon glyphicon-info-sign"></span> <?php lang('information'); ?></h4>
        <div class="text fs-16">
        	<p><a href="<?php echo site_url('account/get_account/'.$account['id']); ?>"><?php echo $account['name']; ?></a></p>
            
            <strong><?php lang('Balance'); ?></strong>:
            <?php echo get_money($account['balance']); ?>
        </div>
    </span>
</div> <!-- /.col-md-4 -->
</div> <!-- /.row -->

<div class="h20"></div>


<script>
$('.openModal-account_list').click(function() {
	$('#modal-account_list').click();
});



function calc_payment()
{
	var old_balance = $('#old_balance').val();
	var payment = $('#payment').val();
	
	if(old_balance == ''){old_balance = 0;}
	if(payment == ''){payment = 0;}
	
	<?php if(isset($_GET['get_money'])): ?>
		var new_balance = parseFloat(old_balance) - parseFloat(payment);
		$('#new_balance').val(parseFloat(new_balance).toFixed(2));
	<?php else: ?>
		var new_balance = parseFloat(old_balance) + parseFloat(payment);
		$('#new_balance').val(parseFloat(new_balance).toFixed(2));
	<?php endif; ?>
}
</script>


</div> <!-- /#transactions -->





<div class="tab-pane fade in" id="history">
	<?php get_log_table(array('invoice_id'=>$invoice['id']), 'ASC'); ?>
</div> <!-- #history -->

</div> <!-- /#myTabContent -->

<?php calc_account_balance($invoice['account_id']); ?>