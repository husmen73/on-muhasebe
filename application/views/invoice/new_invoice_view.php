<?php if(isset($_GET['sell'])){$text['in_out'] = get_lang('input');} else if(isset($_GET['buy'])){$text['in_out'] = get_lang('output');} else{exit('What is the purpose?');} ?>


<ol class="breadcrumb">
	<li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
	<li><a href="<?php echo site_url('invoice'); ?>"><?php lang('Buying-Selling'); ?></a></li>
	<li class="active">
  	<?php if(isset($_GET['buy'])): ?>
    	<?php lang('New Buying Receipt'); ?>
    <?php else: ?>
    	<?php lang('New Sales Receipt'); ?>
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
                'account_name'=>'account_name')); ?>
            </div>
            
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



<div class="row">
<div class="col-md-8">

<?php
$invoice['account_id'] = '';
$account['account_name'] = '';
$invoice['description'] = '';

if(isset($_POST['add']) and is_log())
{
	$continue = true;
	$this->form_validation->set_rules('account_id', get_lang('Account Card'), 'required|digits');
	$this->form_validation->set_rules('account_name', get_lang('Account Name'), 'required|min_length[3]|max_length[30]');

	if ($this->form_validation->run() == FALSE)
	{
		alertbox('alert-danger', '', validation_errors());
	}
	else
	{
		$invoice['date'] = $this->input->post('date').' '.date('H:i:s');;
		$invoice['account_id'] = $this->input->post('account_id');
		$invoice['description'] = $this->input->post('description');
		
		$invoice['type'] = 'invoice';
		if(isset($_GET['sell'])){$invoice['in_out'] = '1';} else if(isset($_GET['buy'])){$invoice['in_out'] = '0';} 
			
		$invoice_id = add_invoice($invoice);
		
		if($invoice_id > 0)
		{
			$data['type'] = 'invoice';
			$data['invoice_id'] = $invoice_id;
			$data['account_id'] = $invoice['account_id'];
			$data['title'] = get_lang('New Receipt');
			$data['description'] = get_lang('Created a new receipt.').'['.$text['in_out'].']';
			add_log($data);
			
			alertbox('alert-success', get_lang('Operation is Successful'));	
			
			redirect(site_url('invoice/view/'.$invoice_id));
		}
		else { alertbox('alert-danger', get_lang('Error!')); }
	}
}
?>


<form name="form_new_product" id="form_new_product" action="" method="POST" class="validation">
	
    <div class="row">
    	<div class="col-md-6">
            <div class="form-group">
                <label for="date" class="control-label ff-1 fs-16"><?php lang('Date of Receipt'); ?></label>
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
                    <input type="text" id="account_name" name="account_name" class="form-control input-lg ff-1 pointer required" placeholder="<?php lang('Select Account'); ?>" minlength="3" maxlength="30" value="<?php echo $account['account_name']; ?>" readonly>
                </div>
            </div> <!-- /.form-group -->
    	</div> <!-- /.col-md-6 -->
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="description" class="control-label ff-1 fs-16"><?php lang('Description'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="description" name="description" class="form-control input-lg ff-1" placeholder="<?php lang('Description'); ?>" minlength="3" maxlength="50" value="<?php echo $invoice['description']; ?>">
                </div>
            </div> <!-- /.form-group --> 
        </div> <!-- /.col-md-12 -->
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
</script>