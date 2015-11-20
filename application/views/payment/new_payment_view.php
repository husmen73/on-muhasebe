<?php if(isset($_GET['get_money'])){$text['in_out'] = get_lang('input');} else if(isset($_GET['give_money'])){$text['in_out'] = get_lang('output');} else{exit('What is the purpose?');} ?>

<ol class="breadcrumb">
	<li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
	<li><a href="<?php echo site_url('payment'); ?>"><?php lang('Payment'); ?></a></li>
	<li class="active">
  	<?php if(isset($_GET['get_money'])): ?>
    	<?php lang('Get Money'); ?>
    <?php else: ?>
    	<?php lang('Give Money'); ?>
    <?php endif; ?>
	</li>
</ol>





    <!-- Button trigger modal -->
    <a data-toggle="modal" href="#myModal" id="modal-account_list"></a>
    
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title"><?php lang('Account List'); ?></h4>
            </div>
            <div class="modal-body">
                <?php get_account_list(array('account_id'=>'account_id',
                'account_name'=>'account_name',
				'account_balance'=>'old_balance',
				'RUN'=>'calc_payment();')); ?>
            </div>
            
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



<div class="row">
<div class="col-md-8">

<?php
$invoice['account_id'] = '';
$account['name'] = '';
$invoice['description'] = '';

if(isset($_POST['add']) and is_log())
{
	$continue = true;
	$this->form_validation->set_rules('account_id', get_lang('Account Card'), 'required|digits');
	$this->form_validation->set_rules('account_name', get_lang('Account Name'), 'required|min_length[3]|max_length[30]');
	$this->form_validation->set_rules('payment', get_lang('Payment'), 'required|number|max_length[12]');

	if ($this->form_validation->run() == FALSE)
	{
		alertbox('alert-danger', '', validation_errors());
	}
	else
	{
		$invoice['date'] = $this->input->post('date').' '.date('H:i:s');;
		$invoice['account_id'] = $this->input->post('account_id');
		$invoice['description'] = $this->input->post('description');
		$post['payment'] = $this->input->post('payment');
		$invoice['val_1'] = $this->input->post('payment_type');
		$invoice['val_2'] = $this->input->post('fall_due_on');
		$invoice['val_3'] = $this->input->post('checks_serial_no');
		
		$invoice['type'] = 'payment';
		if(isset($_GET['give_money'])){$invoice['in_out'] = '1';} else if(isset($_GET['get_money'])){$invoice['in_out'] = '0';} 
		
		
		$account = get_account($invoice['account_id']);
		
		if($invoice['in_out'] == '1')
		{
			$invoice['grand_total'] = $post['payment'];
		}
		else
		{
			$invoice['grand_total'] = '-'.$post['payment'];
		}
			
		$invoice_id = add_invoice($invoice);
		
		if($invoice_id > 0)
		{
			calc_account_balance($invoice['account_id']);
			$data['type'] = 'invoice';
			$data['invoice_id'] = $invoice_id;
			$data['account_id'] = $invoice['account_id'];
			$data['title'] = get_lang('New Receipt');
			$data['description'] = get_lang('New payment').' ['.$text['in_out'].']';
			add_log($data);
			
			alertbox('alert-success', get_lang('Operation is Successful'));	
			
			redirect(site_url('payment/view/'.$invoice_id));
			
		}
		else { alertbox('alert-danger', get_lang('Error!')); }
	}
}
?>


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
    	<div class="col-md-4">
            <div class="form-group">
                <label for="old_balance" class="control-label ff-1 fs-16"><?php lang('Old Balance'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                    <input type="text" id="old_balance" name="old_balance" class="form-control input-lg ff-1 number" placeholder="0.00" value="" readonly="readonly">
                </div>
            </div> <!-- /.form-group -->
    	</div> <!-- /.col-md-4 -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="payment" class="control-label ff-1 fs-16"><?php lang('Payment'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                    <input type="text" id="payment" name="payment" class="form-control input-lg ff-1 required number" placeholder="0.00" maxlength="10" value="" onkeypress="calc_payment();" onkeyup="calc_payment();">
                </div>
            </div> <!-- /.form-group -->
    	</div> <!-- /.col-md-4 -->
        <div class="col-md-4">
            <div class="form-group">
                <label for="new_balance" class="control-label ff-1 fs-16"><?php lang('New Balance'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon pointer"><span class="glyphicon glyphicon-usd"></span></span>
                    <input type="text" id="new_balance" name="new_balance" class="form-control input-lg ff-1 number" placeholder="0.00" value="" readonly="readonly">
                </div>
            </div> <!-- /.form-group -->
    	</div> <!-- /.col-md-4 -->
    </div> <!-- /.row -->

    <div class="row">
    	<div class="col-md-4">
        	<div class="form-group">
                <label for="payment_type" class="control-label ff-1 fs-16"><?php lang('Payment Type'); ?></label>
                <select name="payment_type" id="payment_type" class="form-control ff-1 input-lg">
                	<option value="cash"><?php lang('Cash'); ?></option>
                    <option value="checks"><?php lang('Checks'); ?></option>
                </select>
                
                <script>
					$('#payment_type').change(function() {
						if($('#payment_type').val() == 'checks'){ $('#payment_type_checks_avtice').show('blonde'); }
						else{ $('#payment_type_checks_avtice').hide('blonde'); }
					});
				</script>
                
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
                                <input type="text" id="fall_due_on" name="fall_due_on" class="form-control input-lg ff-1 required datepicker pointer" placeholder="<?php lang('Fall Due On'); ?>" minlength="3" maxlength="50" value="<?php echo date('Y-m-d'); ?>" readonly>
                            </div>
                        </div> <!-- /.form-group -->
                    </div> <!-- /.col-md-6 -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="checks_serial_no" class="control-label ff-1 fs-16"><?php lang('Serial No'); ?></label>
                            <div class="input-prepend input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                                <input type="text" id="checks_serial_no" name="checks_serial_no" class="form-control input-lg ff-1" placeholder="<?php lang('Serial No'); ?>">
                            </div>
                        </div> <!-- /.form-group --> 
                    </div> <!-- /.col-md-6 -->
                </div> <!-- /.row -->
                
            </div> <!-- /#payment_type_checks_avtice --> 
            <script> $('#payment_type_checks_avtice').hide(); </script>
            <div class="form-group">
                <label for="description" class="control-label ff-1 fs-16"><?php lang('Description'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="description" name="description" class="form-control input-lg ff-1" placeholder="<?php lang('Description'); ?>" minlength="3" maxlength="50" value="<?php echo $invoice['description']; ?>">
                </div>
            </div> <!-- /.form-group --> 
        </div> <!-- /.col-md-8 -->
    </div> <!-- /.row -->
    
    <div class="text-right">
    	<input type="hidden" name="log_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
        <input type="hidden" name="add" />
        <button class="btn btn-default btn-lg btn-success"><?php lang('Next'); ?> &raquo;</button>
    </div> <!-- /.text-right -->
</form>
</div> <!-- /.col-md-8 -->
<div class="col-md-4">
	<span class="help-block note">
        <h4><span class="glyphicon glyphicon-info-sign"></span> <?php lang('information'); ?></h4>
        
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
	var old_balance = $('#old_balance').val(); old_balance = old_balance.replace(',','');
	var payment = $('#payment').val();
	
	if(old_balance == ''){old_balance = 0;}
	if(payment == ''){payment = 0;}
	
	<?php if(isset($_GET['get_money'])): ?>
		var new_balance = parseFloat(old_balance) - parseFloat(payment);
		$('#new_balance').val(parseFloat(new_balance).toFixed(2));
	<?php else: ?>
		var new_balance = parseInt(old_balance) + parseFloat(payment);
		$('#new_balance').val(parseFloat(new_balance).toFixed(2));
	<?php endif; ?>
}
</script>