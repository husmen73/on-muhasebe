<ol class="breadcrumb">
  <li><a href="<?php echo site_url(); ?>"><?php lang('Dashboard'); ?></a></li>
  <li><a href="<?php echo site_url('account'); ?>"><?php lang('Account'); ?></a></li>
  <li class="active"><?php lang('New Account'); ?></li>
</ol>

<div class="row">
<div class="col-md-8">

<?php
$account['code'] = '';
$account['name'] = '';
$account['name_surname'] = '';
$account['balance'] = '';
$account['phone'] = '';
$account['gsm'] = '';
$account['email'] = '';
$account['address'] = '';
$account['county'] = '';
$account['city'] = '';
$account['description'] = '';


if(isset($_POST['add']) and is_log())
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
		$account['balance'] = $this->input->post('balance');
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
				$i = is_account_code($account['code']);
			}
		}
		else
		{
			// Have barcode?
			if(is_account_code($account['code']))
			{
				alertbox('alert-danger', get_lang('This barcode is found in the database.'));
				$continue = false;
			}
		}
		
	
		if($continue)
		{
			$account_id = add_account($account);
			if($account_id > 0)
			{
				alertbox('alert-success', get_lang('Operation is Successful'), '');	
				$log['date'] = $this->input->post('log_time');
				$log['type'] = 'account';
				$log['title']	= get_lang('Account');
				$log['description'] = get_lang('Created a new account card.');
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
                    <input type="text" id="code" name="code" class="form-control input-lg ff-1" placeholder="<?php lang('Barcode Code'); ?>" minlength="3" maxlength="30" value="<?php echo $account['code']; ?>">
                </div>
                
            </div>
            <div class="form-group">
                <label for="name" class="control-label ff-1 fs-16"><?php lang('Account Name'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="name" name="name" class="form-control input-lg ff-1 required" placeholder="<?php lang('Account Name'); ?>" minlength="3" maxlength="30" value="<?php echo $account['name']; ?>">
                </div>
            </div>     
        </div> <!-- /.col-md-6 -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="name_surname" class="control-label ff-1 fs-16"><?php lang('Name and Surname'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="name_surname" name="name_surname" class="form-control input-lg ff-1" placeholder="<?php lang('Name Surname'); ?>" value="<?php echo $account['name_surname']; ?>" minlengt="3" maxlength="30">
                </div>
            </div> <!-- /.form-group -->
            <div class="form-group">
                <label for="balance" class="control-label ff-1 fs-16"><?php lang('Balance'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                    <input type="text" id="balance" name="balance" class="form-control input-lg ff-1 number" placeholder="0.00" value="<?php echo $account['balance']; ?>">
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
                    <input type="text" id="phone" name="phone" class="form-control input-lg ff-1 digits" minlength="7" maxlength="16" value="<?php echo $account['phone']; ?>">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-4 -->
        <div class="col-md-4">
        	<div class="form-group">
                <label for="gsm" class="control-label ff-1 fs-16"><?php lang('Gsm'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></span>
                    <input type="text" id="gsm" name="gsm" class="form-control input-lg ff-1 digits" minlength="7" maxlength="16" value="<?php echo $account['gsm']; ?>">
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- /.col-md-4 -->
        <div class="col-md-4">
        	<div class="form-group">
                <label for="email" class="control-label ff-1 fs-16"><?php lang('E-mail'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                    <input type="text" id="email" name="email" class="form-control input-lg ff-1 email" minlength="6" maxlength="50" value="<?php echo $account['email']; ?>">
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
                   <textarea class="form-control" name="address" id="address" style="height:130px;" minlength="3" maxlength="250"><?php echo $account['address']; ?></textarea>
                </div>
            </div> <!-- /.form-group -->
        </div> <!-- col-md-8 -->
        <div class="col-md-4">
        	<div class="form-group">
                <label for="county" class="control-label ff-1 fs-16"><?php lang('County'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="county" name="county" class="form-control input-lg ff-1" minlength="2" maxlength="20" value="<?php echo $account['county']; ?>">
                </div>
            </div> <!-- /.form-group -->
            <div class="form-group">
                <label for="city" class="control-label ff-1 fs-16"><?php lang('City'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="city" name="city" class="form-control input-lg ff-1" minlength="2" maxlength="20" value="<?php echo $account['city']; ?>">
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
    

    <div class="text-right">
    	<input type="hidden" name="log_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
        <input type="hidden" name="add" />
        <button class="btn btn-default btn-lg btn-success"><?php lang('Save'); ?> &raquo;</button>
    </div> <!-- /.text-right -->
</form>
</div> <!-- /.col-md-8 -->
<div class="col-md-4">
	<span class="help-block note">
        <h4><span class="glyphicon glyphicon-info-sign"></span> <?php lang('information'); ?></h4>
        <ul class="note">
            <li><?php lang('Barcode code may be empty.'); ?> <?php lang('Barcode code is empty, automatic code occurs.'); ?></li>
            <li><?php lang('Account name field, the firm / company name, you can write.'); ?></li>
            <li><?php lang('Write your e-mail address correctly. Because mails can send invoices and information.'); ?></li>
        </ul>
    </span>
</div> <!-- /.col-md-4 -->
</div> <!-- /.row -->

<div class="h20"></div>