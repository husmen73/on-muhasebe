<?php include_once('../../header.php'); ?>

<?php change_navigation(get_lang('Add Product')); ?>

<?php
if(isset($_POST['btn_add']))
{
	$datetime		=	safety_filter($_POST['datetime']);
	$code			=	safety_filter($_POST['code']);
	$name			=	safety_filter($_POST['name']);
	$cost_price		=	safety_filter($_POST['cost_price']);
	$sale_price		=	safety_filter($_POST['sale_price']);
	
	if(!get_log($datetime, get_the_current_user('id'), 'New Product', true))
	{
		$product_id = 0;
		$product_id = add_product($code, $name, $sale_price, $cost_price);
		if($product_id > 0)
		{
			add_log($datetime, $product_id, '', '', 'New Product', get_lang('Generated new product card'));	
			go_to_page('product.php?product_id='.$product_id.'');
		}
	}
}
?>

<form name="form1" id="form1" action="" method="POST">
	<div class="row">
    	<div class="six columns">
            <fieldset>
                <legend><?php lang('Product'); ?></legend>

                <label for="code"><?php lang('Barcode Code'); ?></label>
                <input type="text" name="code" id="code" value="" maxlength="20" minlength="3" class="required" />

                <label for="name"><?php lang('Product Name'); ?></label>
                <input type="text" name="name" id="name" value="" maxlength="20" minlength="3" class="required" />
                	                    
            </fieldset>
            
            <fieldset>
                <legend><?php lang('Price'); ?></legend>
                <div class="row">
                	<div class="six columns">
                        <label for="cost_price"><?php lang('Cost Price'); ?></label>
                        <input type="text" name="cost_price" id="cost_price" maxlength="20" class="number" value="0.00" />
                    </div>
                    <div class="six columns">
                        <label for="sale_price"><?php lang('Sale Price'); ?></label>
                        <input type="text" name="sale_price" id="sale_price" maxlength="20" class="number" value="0.00" />
                    </div>
                </div>
        	</fieldset>
            
        </div> <!-- /.six columns -->
        <div class="six columns">
        	
        </div> <!-- /.six columns -->
    </div> <!-- /.row -->
    
    <div class="row">
    	<div class="twelve columns">
        	<input type="hidden" name="datetime" id="datetime" value="<?php config('datetime'); ?>" />
        	<input type="submit" name="btn_add" id="btn_add" class="button" value="<?php lang('Add'); ?>  &raquo;" />
        </div>
    </div>
</form>

<?php include_once('../../footer.php'); ?>