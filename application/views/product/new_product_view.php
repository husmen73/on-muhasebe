<ol class="breadcrumb">
  <li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
  <li><a href="<?php echo site_url('product'); ?>"><?php lang('Product'); ?></a></li>
  <li class="active"><?php lang('New Product'); ?></li>
</ol>


<div class="row">
<div class="col-md-8">

<?php
$product['code'] = '';
$product['name'] = '';
$product['description'] = '';
$product['cost_price'] = '';
$product['sale_price'] = '';
$product['tax_rate'] = '';

if(isset($_POST['add_product']) and is_log())
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
		$product['code'] = replace_text_for_utf8($this->input->post('code'));
		$product['name'] = $this->input->post('name');
		$product['description'] = $this->input->post('description');
		$product['cost_price'] = $this->input->post('cost_price');
		$product['sale_price'] = $this->input->post('sale_price');
		$product['tax_rate'] = $this->input->post('tax_rate');
		
		// Have barcode?
		// if the barcode is empty, auto-create
		if($product['code'] == '')
		{ 
			$product['code'] = replace_text_for_utf8($this->input->post('name')); 
			
			// Have barcode?
			for($i = is_product_code($product['code']); $i > 0; $i++)
			{
				$product['code'] = replace_text_for_utf8($this->input->post('name')).'-'.$i; 
				$i = is_product_code($product['code']);
			}
		}
		else
		{
			// Have barcode?
			if(is_product_code($product['code']))
			{
				alertbox('alert-danger', get_lang('This barcode is found in the database. Used for another product.'));
				$continue = false;
			}
		}
	
		if($continue)
		{
			$product_id = add_product($product);
			if($product_id > 0)
			{
				alertbox('alert-success', get_lang('Operation is Successful'), '');	
				$log['date'] = $this->input->post('log_time');
				$log['type'] = 'product';
				$log['title']	= get_lang('Product');
				$log['description'] = get_lang('Created a new product line card.');
				$log['other_id'] = 'product:'.$product_id;
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
                    <input type="text" id="cost_price" name="cost_price" class="form-control input-lg ff-1 number" placeholder="0.00" value="<?php echo $product['cost_price']; ?>">
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
        </div> <!-- /.col-md- -->
    </div> <!-- /.row -->

    <div class="text-right">
    	<input type="hidden" name="log_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
        <input type="hidden" name="add_product" />
        <button class="btn btn-default btn-lg"><?php lang('Save'); ?> &raquo;</button>
    </div> <!-- /.text-right -->
</form>
</div> <!-- /.col-md-8 -->
<div class="col-md-4">
	<span class="help-block note">
        <h4><span class="glyphicon glyphicon-info-sign"></span> <?php lang('information'); ?></h4>
        <ul class="note">
            <li><?php lang('You must purchase the product to change the amount of stock.'); ?></li>
            <li><?php lang('Use the handheld terminal with barcode code.'); ?></li>
            <li><?php lang('You can leave blank the value of tax.'); ?></li>
            <li><?php lang('You can write only the numerical value of the tax figure.'); ?></li>
        </ul>
    </span>
</div> <!-- /.col-md-4 -->
</div> <!-- /.row -->

<div class="h20"></div>