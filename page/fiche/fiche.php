<?php include_once('../../header.php'); ?>

<?php change_navigation(get_lang('Fiche')); ?>

<?php if(!isset($_GET['fiche_id'])) { alert_box('alert', 'No Fıche ID'); exit; } ?>

<?php
if(isset($_POST['btn_next']))
{
	$datetime		=	safety_filter($_POST['datetime']);
	$type			=	safety_filter($_POST['type']);
	$date			=	safety_filter($_POST['date']);
	$current_id		=	safety_filter($_POST['current_id']);
	

	if(!get_log($datetime, get_the_current_user('id'), 'New Fiche', true))
	{
		$fiche_id = add_fiche($type, $date, $current_id);
		if($fiche_id > 0)
		{
			add_log($datetime, '', $current_id, $fiche_id, 'New Fiche', 'Generated new fiche');	
			go_to_page('fiche.php?fiche_id='.$fiche_id.'');
		}
	}
}
?>

<div class="row hide-on-print">
	<div class="eight columns">
		<div class="button-bar">
			<ul class="button-group">
				<li><a href="?fiche_id=<?php the_fiche('id'); ?>&print" target="_blank" class="button secondary small"><?php lang('Print'); ?></a></li>
            	<li><a href="<?php url('page'); ?>/logs/logs.php?fiche_id=<?php the_fiche('id'); ?>" class="button secondary small"><?php lang('Logs'); ?></a></li>
			</ul>
		</div>
    </div> <!-- /.eight columns -->
    <div class="four columns text-right">
    <?php if(get_the_fiche('status') == 'publish') : ?>
    	<a href="?fiche_id=<?php the_fiche('id'); ?>&status=delete" class="button alert small"><?php lang('delete'); ?></a>
    <?php else : ?>
    	<a href="?fiche_id=<?php the_fiche('id'); ?>&status=publish" class="button success small"><?php lang('publish'); ?></a>
    <?php endif; ?>
    </div> <!-- /.four columns -->
</div> <!-- /.row -->


<?php
if(isset($_GET['status']))
{
	$status = safety_filter($_GET['status']);
	if($status == 'publish' or $status == 'delete'){} else { alert_box('alert', 'No Status'); exit; }
	
	mysql_query("UPDATE $database->fiche SET status='$status', tax_rate='0' WHERE id='".get_the_fiche('id')."'");	
	if($status == 'delete') { mysql_query("UPDATE $database->fiche_items SET status='$status' WHERE fiche_id='".get_the_fiche('id')."'"); }
	
	calc_current_balance(get_the_fiche('current_id'));
	calc_fiche_total(get_the_fiche('id'));
	
	$query_fiche_items = mysql_query("SELECT * FROM $database->fiche_items WHERE fiche_id='".get_the_fiche('id')."'");
	while($list_fiche_items = mysql_fetch_assoc($query_fiche_items))
	{
		calc_product_amount($list_fiche_items['product_id']);
	}
	
	go_to_page('?fiche_id='.get_the_fiche('id').'');
}
?>

<?php 
	if(get_the_fiche('type') == 'input') { box_product_list('product_id', 'product_code', 'price', '');  }
	else { box_product_list('product_id', 'product_code', '', 'price');  }
?>
            
<div class="row">
    <div class="three columns">
        <!-- type -->
        <div class="row collapse">
            <div class="two mobile-one columns">
              <span class="prefix"><label for="type" class="has-tip tip-top" title="<?php lang('Type'); ?>"  ><img src="<?php url('theme'); ?>/images/icon/16/invoice.png" /></label></span>
            </div>
            <div class="ten mobile-three columns">
        	<input type="text" name="type" id="type" value="<?php lang(get_the_fiche('type')); ?>" readonly />
            </div>
        </div>
        <!-- /type -->
    </div>
    <div class="three columns">
        <!-- date -->
        <div class="row collapse">
            <div class="two mobile-one columns">
              <span class="prefix"><label for="date" class="has-tip tip-top" title="<?php lang('Date'); ?>"  ><img src="<?php url('theme'); ?>/images/icon/16/calendar_1.png" /></label></span>
            </div>
            <div class="ten mobile-three columns">
              <input type="text" name="date" id="date" class="required" maxlength="40" minlength="10" value="<?php the_fiche('date'); ?>" readonly />
            </div>
        </div>
        <!-- /date -->
    </div> <!-- /.three columns -->
    <div class="six columns">
        <!-- current card -->
        <div class="row collapse">
            <div class="one mobile-one columns">
              <span class="prefix"><span class="has-tip tip-top" title="<?php lang('Current Card'); ?>"><img src="<?php url('theme'); ?>/images/icon/16/customers.png" /></span></span>
            </div>
            <div class="eleven mobile-three columns">
              <input type="text" name="current_code" id="current_code" maxlength="20" minlength="3" class="required" value="<?php current_card(get_the_fiche('current_id'), 'name'); ?>" readonly />
            </div>
        </div>
        <!-- /current card -->
    </div> <!-- /.six columns -->
</div> <!-- /.row -->


<?php
if(isset($_GET['add_item_success']))
{
	alert_box('success', get_lang('Successful').' ['.$_GET['add_item_success'].']');
}

if(isset($_GET['success_update_tax']))
{
	alert_box('success', get_lang('Successful').'');
}

if(isset($_POST['btn_add']))
{
	$continue = true;
	$datetime			=	safety_filter($_POST['datetime']);
	$product_code		=	safety_filter($_POST['product_code']);
	$product_id			=	safety_filter($_POST['product_id']);
	$quantity			=	safety_filter($_POST['quantity']);
	$price				=	safety_filter($_POST['price']);
	
	$product['id'] = 0;
	$query_product = mysql_query("SELECT * FROM $database->products WHERE code='$product_code'");
	while($list_prodoct = mysql_fetch_assoc($query_product))
	{
		$product['id'] =	$list_prodoct['id'];
		
		if($price == '')	
		{	
			if(get_the_fiche('type') == 'input')		{ $price = $list_prodoct['cost_price']; }
			else if(get_the_fiche('type') == 'output') 	{ $price = $list_prodoct['sale_price']; }
		}
	}
	
	if($product['id'] == 0)
	{
		alert_box('alert', get_lang('Product card in the database has been found.').' ['.$product_code.']');
		$continue = false;
	}
	else
	{
		$product_id = $product['id'];
	}
	
	if($continue == true)
	{
		if(!get_log($datetime, get_the_current_user('id'), 'Add Item', true))
		{	
			if(add_fiche_item(get_the_fiche('id'), get_the_fiche('date'), get_the_fiche('type'), get_the_fiche('current_id'), $product_id, $quantity, $price))
			{
				add_log($datetime, $product_id, get_the_fiche('current_id'), get_the_fiche('id'), 'Add Item', 'Product added');
				go_to_page('fiche.php?fiche_id='.get_the_fiche('id').'&add_item_success='.$product_code.'');
			}
		}
	}
}

if(isset($_POST['update_subtotal']))
{
	$tax_rate = safety_filter($_POST['tax_rate']);
	mysql_query("UPDATE $database->fiche SET tax_rate='$tax_rate' WHERE id='".get_the_fiche('id')."'");
	calc_fiche_total($fiche_id);
	go_to_page('?fiche_id='.get_the_fiche('id').'&success_update_tax');
}
?>


<style>
form .big_text {
	font-size:36px;
	padding:10px;
	height:60px;
}
.prefix.big	{
	height:60px;
}
#product_code:focus	{
	background-color:#F4EEC1;
	}
</style>

<form name="form1" id="form1" action="?fiche_id=<?php the_fiche('id'); ?>" method="POST" >
<div class="row hide-on-print">
	<div class="six columns">
    	<!-- product code -->
        <div class="row collapse">
            <div class="two mobile-one columns">
            	<span class="prefix big">
                	<a href="#" class="has-tip tip-top"  data-reveal-id="box_product_list" title="<?php lang('Product List'); ?>"><img src="<?php url('theme'); ?>/images/icon/48/barcode.png" /></a>
                </span>
            </div>
            <div class="ten mobile-three columns">
            	<input type="text" name="product_code" id="product_code" maxlength="20" minlength="3" class="required big_text" value="" placeholder="<?php lang('barcode scaner'); ?>" />
                <input type="hidden" name="product_id" id="product_id" value="" />
            </div>
        </div>
        <!-- /product code -->
    </div> <!-- /.six columns -->
    <div class="two columns">
    	<!-- quantity -->
    	<div class="row collapse">
            <div class="three mobile-one columns">
            	<span class="prefix big">
                	<span class="has-tip tip-top" title="<?php lang('Quantity'); ?>">#</span>
                </span>
            </div>
            <div class="nine mobile-three columns">
            	<input type="text" name="quantity" id="quantity" maxlength="20" minlength="1" class="required number big_text just_money" value="1" />
            </div>
        </div>
        <!-- /quantity -->
    </div> <!-- /.two columns -->
    <div class="three columns">
    	<!-- price -->
    	<div class="row collapse">
            <div class="two mobile-one columns">
            	<span class="prefix big">
                	<span class="has-tip tip-top" title="<?php lang('Price'); ?>"><img src="<?php url('theme'); ?>/images/icon/16/cur_dollar.png" /></span>
                </span>
            </div>
            <div class="ten mobile-three columns">
            	<input type="text" name="price" id="price" maxlength="20" minlength="1" class="number big_text just_money" value="" />
            </div>
        </div>
        <!-- /price -->
    </div> <!-- /.two columns -->
    <div class="one columns end">
    	<input type="hidden" name="datetime" id="datetime" value="<?php config('datetime'); ?>" />
    	<input type="submit" name="btn_add" id="btn_add" class="button radius" value="<?php lang('Add'); ?>" style="width:64px; height:60px;" />
    </div> <!-- /.two columns -->    
</div> <!-- /.row -->

<script>
	document.getElementById('product_code').focus();
</script>

</form>        

<?php if(get_the_fiche('fiche_items') == 0) : ?>
<div class="row">
	<div class="six columns text-center end">
    	<img src="<?php url('theme'); ?>/images/barcode_scaner.png" />
    </div> <!-- 7.twelve columns -->
</div> <!-- /.row --> 
<?php else : ?>


<?php
if(isset($_GET['success_delete_item_id']))
{
	$delete_item_id = safety_filter($_GET['success_delete_item_id']);
	alert_box('success', get_lang('Product has been deleted.').' ['.get_product(get_fiche_item($delete_item_id, 'product_id'), 'code').']');	
}

if(isset($_GET['delete_item_id']))
{
	$delete_item_id	= safety_filter($_GET['delete_item_id']);
	
	mysql_query("UPDATE $database->fiche_items SET status='delete' WHERE id='$delete_item_id'");
	if(mysql_affected_rows() > 0)
	{
		calc_fiche_total(get_the_fiche('id'));
		calc_current_balance(get_the_fiche('current_id'));
		calc_product_amount(get_fiche_item($delete_item_id, 'product_id'));	
		
		add_log(get_config('datetime'), get_fiche_item($delete_item_id, 'product_id'), get_the_fiche('current_id'), get_the_fiche('id'), 'Delete Item', 'Product has been deleted.');
		
		go_to_page('?fiche_id='.get_the_fiche('id').'&success_delete_item_id='.$delete_item_id.'');
	}
}
?>


<table width="100%">
	<thead>
    	<tr>
        	<th class="hide-on-print" width="40"></th>
            <th><?php lang('Product Code'); ?></th>
            <th><?php lang('Product Name'); ?></th>
            <th><?php lang('Quantity'); ?></th>
            <th><?php lang('Price'); ?></th>
            <th><?php lang('Total'); ?></th>
        </tr>
	</thead>
    <tbody>
    <?php
	$query_fiche_items = mysql_query("SELECT * FROM $database->fiche_items WHERE status='publish' AND fiche_id='".get_the_fiche('id')."'");
	while($list_fiche_items = mysql_fetch_assoc($query_fiche_items))
	{
		$fiche_items['id']				=	$list_fiche_items['id'];
		$fiche_items['status']			=	$list_fiche_items['status'];
		$fiche_items['type']			=	$list_fiche_items['type'];
		$fiche_items['date']			=	$list_fiche_items['date'];
		$fiche_items['fiche_id']		=	$list_fiche_items['fiche_id'];
		$fiche_items['current_id']		=	$list_fiche_items['current_id'];
		$fiche_items['product_id']		=	$list_fiche_items['product_id'];
		$fiche_items['quantity']		=	$list_fiche_items['quantity'];
		$fiche_items['cost_price']		=	$list_fiche_items['cost_price'];
		$fiche_items['price']			=	$list_fiche_items['price'];
		$fiche_items['total']			=	$list_fiche_items['total'];
		
		$fiche_items['product_code'] 	= get_product($fiche_items['product_id'], 'code');
		
		echo '
		<tr>
			<td class="hide-on-print"><a href="?fiche_id='.get_the_fiche('id').'&delete_item_id='.$fiche_items['id'].'" class="has-tip tip-top" title="['.$fiche_items['product_code'].'] '.get_lang('Delete').'"><img src="'.get_url('theme').'/images/icon/16/trash.png" /></a></td>
			<td>'.$fiche_items['product_code'].'</td>
			<td>'.get_product($fiche_items['product_id'], 'name').'</td>
			<td class="text-center">'.round($fiche_items['quantity'],2).'</td>
			<td class="text-right">'.get_mf($fiche_items['price']).'</td>
			<td class="text-right">'.get_mf($fiche_items['total']).'</td>
		</tr>
		';
	}
	?>
    </tbody>
</table>


<!-- SUB TOTAL -->
<form name="form_subtotal" id="form_subtotal" action="?fiche_id=<?php the_fiche('id'); ?>" method="POST">
<div class="row">
	<div class="two columns">
    	<!-- total -->
    	<div class="row collapse">
            <div class="three mobile-one columns">
            	<span class="prefix">
                	<span class="has-tip tip-top" title="<?php lang('Total'); ?>"><img src="<?php url('theme'); ?>/images/icon/16/cur_dollar.png" /></span>
                </span>
            </div>
            <div class="nine mobile-three columns">
            	<input type="text" name="total" id="total" maxlength="20" minlength="1" class="number just_money" value="<?php mf(get_the_fiche('total')); ?>" readonly />
            </div>
        </div>
        <!-- /total -->
    </div> <!-- /.two columns -->
    <div class="two columns">
    	<!-- tax -->
    	<div class="row collapse">
            <div class="three mobile-one columns">
            	<span class="prefix">
                	<span class="has-tip tip-top" title="<?php lang('Tax Rate'); ?>"><img src="<?php url('theme'); ?>/images/icon/16/percent.png" /></span>
                </span>
            </div>
            <div class="nine mobile-three columns">
            	<input type="text" name="tax_rate" id="tax_rate" onkeyup="calc_taxt();"  maxlength="3" minlength="1" class="number just_money" value="<?php the_fiche('tax_rate'); ?>" />
            </div>
        </div>
        <!-- /tax -->
    </div> <!-- /.two columns -->
    <div class="two columns">
    	<!-- tax -->
    	<div class="row collapse">
            <div class="three mobile-one columns">
            	<span class="prefix">
                	<span class="has-tip tip-top" title="<?php lang('Tax'); ?>"><img src="<?php url('theme'); ?>/images/icon/16/plus.png" /></span>
                </span>
            </div>
            <div class="nine mobile-three columns">
            	<input type="text" name="tax" id="tax" maxlength="20" minlength="1" class="number just_money" value="<?php mf(get_the_fiche('tax')); ?>" readonly />
            </div>
        </div>
        <!-- /tax -->
    </div> <!-- /.two columns -->
    <div class="two columns">
    	<!-- tax -->
    	<div class="row collapse">
            <div class="three mobile-one columns">
            	<span class="prefix">
                	<span class="has-tip tip-top" title="<?php lang('Grand Total'); ?>"><img src="<?php url('theme'); ?>/images/icon/16/cur_dollar.png" /></span>
                </span>
            </div>
            <div class="nine mobile-three columns">
            	<input type="text" name="grand_total" id="grand_total" maxlength="20" minlength="1" class="number just_money" value="<?php mf(get_the_fiche('grand_total')); ?>" readonly />
            </div>
        </div>
        <!-- /tax -->
    </div> <!-- /.two columns -->
    <div class="two columns end">
    	<input type="hidden" name="update_subtotal" id="update_subtotal" value="" />
		<a href="#" onClick="document.getElementById('form_subtotal').submit();" class="button secondary small hide-on-print"><?php lang('Update'); ?></a>
    </div> <!-- /.two columns -->
</div> <!-- /.row -->

<script>
function calc_taxt()
{
	var total = document.getElementById('total').value;
	var tax_rate = document.getElementById('tax_rate').value;
	var tax = 0;
	
	tax = (total / 100) * tax_rate;
	document.getElementById('grand_total').value = parseFloat(parseFloat(tax) + parseFloat(total)).toFixed(2);
}
</script>
</form>
<?php endif; ?>

<?php if(isset($_GET['print'])) { echo '<script>print();</script>';	} ?>

<?php include_once('../../footer.php'); ?>