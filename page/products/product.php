<?php include_once('../../header.php'); ?>

<?php change_navigation(get_lang('Product Card')); ?>

<?php if(!isset($_GET['product_id'])){ alert_box('alert', get_lang('No Product ID number.')); exit;	} ?>

<script>
function popup_barcode_print()
{
		window.open ('<?php echo url(''); ?>/include/class/barcode/barcode_show.php?barcode=<?php the_product('code'); ?>&print='+ true +'','mywindow','menubar=0,resizable=0,width=10,height=10');	
}
</script>

<div class="row">
	<div class="ten columns">
        <div class="button-bar">
          <ul class="button-group">
            <li><a href="#" onClick="popup_barcode_print();" class="button small secondary"><?php lang('Print Barcode'); ?></a></li>
            <li><a href="<?php url('page'); ?>/logs/logs.php?product_id=<?php the_product('id'); ?>" class="button secondary small"><?php lang('Logs'); ?></a></li>
          </ul>
        </div>
	</div> <!-- /.ten -->
    <div class="two columns text-right">
    	<?php if(get_the_product('status') == 'publish') : ?>
            <a href="?product_id=<?php echo get_the_product('id'); ?>&status=delete" class="button small alert"><?php lang('Delete'); ?></a>
        <?php else : ?>
            <a href="?product_id=<?php echo get_the_product('id'); ?>&status=publish" class="button small success"><?php lang('Publish'); ?></a>
        <?php endif; ?>
    </div> <!-- /.two columns -->
</div> <!-- /.row -->


<?php
if(isset($_GET['success']))
{
	if($_GET['success'] == 'update')
	{
		alert_box('success', get_lang('Updated Successfully'));	
	}
}


if(isset($_GET['status']))
{
	$status = safety_filter($_GET['status']);
	if($status == 'delete' or $status == 'publish'){} else { alert_box('alert', 'NO STATUS VALUE');	}
	mysql_query("UPDATE $database->products SET status='$status' WHERE id='".get_the_product('id')."'");
	if(mysql_affected_rows() > 0)
	{
		go_to_page('product.php?product_id='.$product_id.'&success=update');
	}
	else
	{
		alert_box('alert', mysql_error());	
	}
}


if(isset($_POST['btn_update']))
{
	$datetime		=	safety_filter($_POST['datetime']);
	$code			=	safety_filter($_POST['code']);
	$name			=	safety_filter($_POST['name']);
	$cost_price		=	safety_filter($_POST['cost_price']);
	$sale_price		=	safety_filter($_POST['sale_price']);
	
	if(!get_log($datetime, get_the_current_user('id'), 'Update Product', false))
	{
		if(update_product(get_the_product('id'), $code, $name, $cost_price, $sale_price))
		{
			add_log($datetime, get_the_product('id'), '', 'Update Product', get_lang('Product card updated'));	
			go_to_page('product.php?product_id='.$product_id.'&success=update');
		}
	}
}
?>

<form name="form1" id="form1" action="?product_id=<?php echo get_the_product('id'); ?>" method="POST">
	<div class="row">
    	<div class="six columns">
            <fieldset>
                <legend><?php lang('Product'); ?></legend>

                <label for="code"><?php lang('Barcode Code'); ?></label>
                <input type="text" name="code" id="code" maxlength="20" minlength="3" class="required" value="<?php the_product('code'); ?>" readonly />

                <label for="name"><?php lang('Product Name'); ?></label>
                <input type="text" name="name" id="name" maxlength="20" minlength="3" class="required" value="<?php the_product('name'); ?>" />
                    
            </fieldset>
            
            <fieldset>
                <legend><?php lang('Price'); ?></legend>
                <div class="row">
                	<div class="six columns">
                        <label for="cost_price"><?php lang('Cost Price'); ?></label>
                        <input type="text" name="cost_price" id="cost_price" maxlength="20" class="number" value="<?php mf(get_the_product('cost_price')); ?>" />
                    </div>
                    <div class="six columns">
                        <label for="sale_price"><?php lang('Sale Price'); ?></label>
                        <input type="text" name="sale_price" id="sale_price" maxlength="20" class="number" value="<?php mf(get_the_product('sale_price')); ?>" />
                    </div>
                </div>
        	</fieldset>
            
        </div> <!-- /.six columns -->
        <div class="six columns">
        	<fieldset>
                <legend><?php lang('Barcode'); ?></legend>
        		<div class="text-center">
                	<img src="<?php url(''); ?>/include/class/barcode/barcode.php?barcode=<?php the_product('code'); ?>" style="border:1px solid #ccc; padding:4px;" />
                </div>
                <p></p>
        	</fieldset>
        </div> <!-- /.six columns -->
    </div> <!-- /.row -->
    
    <div class="row">
    	<div class="twelve columns">
        	<input type="hidden" name="datetime" id="datetime" value="<?php config('datetime'); ?>" />
        	<input type="submit" name="btn_update" id="btn_update" class="button" value="<?php lang('Update'); ?>" />
        </div>
    </div>
</form>


<?php include_once('../../footer.php'); ?>