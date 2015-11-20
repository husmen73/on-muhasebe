<?php page_access(2); ?>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url(''); ?>"><?php lang('Dashboard'); ?></a></li>
  <li><a href="<?php echo site_url('product'); ?>"><?php lang('Product'); ?></a></li>
  <li class="active"><?php lang('Options'); ?></li>
</ol>

<?php
if(isset($_POST['update_options']))
{
	$data['option_group'] 	= 'product';
	
	# who_can_see_cost_price
	$data['option_key']		= 'who_can_see_cost_price';
	$data['option_value']	= $_POST['who_can_see_cost_price'];
	update_option($data);
	
	# who_can_edit_product_card
	$data['option_key']		= 'who_can_edit_product_card';
	$data['option_value']	= $_POST['who_can_edit_product_card'];
	update_option($data);
}
?>


<?php
$options['who_can_see_cost_price'] = get_option(array('option_group'=>'product', 'option_key'=>'who_can_see_cost_price'));
$options['who_can_edit_product_card'] = get_option(array('option_group'=>'product', 'option_key'=>'who_can_edit_product_card'));
?>

<div class="row">
	<div class="col-md-4">
<form name="form_product_options" id="form_product_options" action="" method="POST">
	
	<div class="form-group">
		<label for="who_can_see_cost_price" class="control-label ff-1 fs-16"><?php lang('Who can see the cost price?'); ?></label>
		<select name="who_can_see_cost_price" id="who_can_see_cost_price" class="form-control input-lg valid">
			<option value="5" <?php if($options['who_can_see_cost_price']['option_value']==5){echo'selected';} ?>><?php lang('Staff'); ?></option>
            <option value="4" <?php if($options['who_can_see_cost_price']['option_value']==4){echo'selected';} ?>><?php lang('Authorized Personnel'); ?></option>
            <option value="3" <?php if($options['who_can_see_cost_price']['option_value']==3){echo'selected';} ?>><?php lang('Chief'); ?></option>
            <option value="2" <?php if($options['who_can_see_cost_price']['option_value']==2){echo'selected';} ?>><?php lang('Admin'); ?></option>
            <option value="1" <?php if($options['who_can_see_cost_price']['option_value']==1){echo'selected';} ?>><?php lang('Super Admin'); ?></option>                      
        </select>
	</div>
    
    
    <div class="form-group">
		<label for="who_can_edit_product_card" class="control-label ff-1 fs-16"><?php lang('Who can edit the product card?'); ?></label>
		<select name="who_can_edit_product_card" id="who_can_edit_product_card" class="form-control input-lg valid">
			<option value="5" <?php if($options['who_can_edit_product_card']['option_value']==5){echo'selected';} ?>><?php lang('Staff'); ?></option>
            <option value="4" <?php if($options['who_can_edit_product_card']['option_value']==4){echo'selected';} ?>><?php lang('Authorized Personnel'); ?></option>
            <option value="3" <?php if($options['who_can_edit_product_card']['option_value']==3){echo'selected';} ?>><?php lang('Chief'); ?></option>
            <option value="2" <?php if($options['who_can_edit_product_card']['option_value']==2){echo'selected';} ?>><?php lang('Admin'); ?></option>
            <option value="1" <?php if($options['who_can_edit_product_card']['option_value']==1){echo'selected';} ?>><?php lang('Super Admin'); ?></option>                      
        </select>
	</div>
          
   
	<div class="text-right">    
    	<input type="hidden" name="update_options" /> 
   		<button class="btn btn-lg btn-success"><?php lang('Save'); ?></button> 
   	</div> <!-- /.text-right -->                
</form>
	</div> <!-- /.col-md-4 -->
</div> <!-- /.row -->