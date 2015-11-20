<?php
/* ----------------------------------------------
	ADD PRODUCT
---------------------------------------------- */
function add_product($code, $name, $cost_price, $sale_price)
{
	global $database;
	
	$code			=	safety_filter($code);
	$name			=	safety_filter($name);
	$cost_price		=	safety_filter($cost_price);
	$sale_price		=	safety_filter($sale_price);
	
	if(strlen($code) < 3)	{ alert_box('alert', get_lang('Not Applicable Product Code')); return false;	}
	if(strlen($name) < 3)	{ alert_box('alert', get_lang('Not Applicable Product Name')); return false;	}
	if(!is_money_format($cost_price)) {	alert_box('alert', get_lang('Cost Price Unavailable')); return false;	}
	if(!is_money_format($sale_price)) {	alert_box('alert', get_lang('Sales Price Unavailable')); return false;	}
	if(mysql_num_rows(mysql_query("SELECT * FROm $database->products WHERE status='publish' AND code='$code'")) > 0)
		{	alert_box('alert', get_lang('This product code exists in the database.')); return false;	}
	
	mysql_query("INSERT INTO $database->products
	(code, name, cost_price, sale_price)
	VALUES
	('$code', '$name', '$sale_price', '$cost_price')");
	if(mysql_affected_rows() > 0)
	{
		return mysql_insert_id();
	}
	else
	{
		alert_box('alert', mysql_error());
	}
}


/* ----------------------------------------------
	UPDATE PRODUCT
---------------------------------------------- */
function update_product($product_id, $code, $name, $cost_price, $sale_price)
{
	global $database;
	
	$code			=	safety_filter($code);
	$name			=	safety_filter($name);
	$cost_price		=	safety_filter($cost_price);
	$sale_price		=	safety_filter($sale_price);
	
	if(strlen($code) < 3)	{ alert_box('alert', get_lang('Not Applicable Product Code')); return false;	}
	if(strlen($name) < 3)	{ alert_box('alert', get_lang('Not Applicable Product Name')); return false;	}
	if(!is_money_format($cost_price)) {	alert_box('alert', get_lang('Cost Price Unavailable')); return false;	}
	if(!is_money_format($sale_price)) {	alert_box('alert', get_lang('Sales Price Unavailable')); return false;	}
	
	$update = mysql_query("UPDATE $database->products SET
	code='$code',
	name='$name',
	cost_price='$cost_price',
	sale_price='$sale_price'
	WHERE 
	id='$product_id'");
	if(mysql_affected_rows() > 0)
	{
		return true;	
	}
	else
	{
		if($update) { 	return false;			}
		else 		{	return false;			}
	}
	
}



/* ----------------------------------------------
	THE PRODUCT
---------------------------------------------- */
if(isset($_GET['product_id']) or isset($_POST['product_id']))
{
	$product_id = 0;
	if(isset($_GET['product_id'])) 			{	$product_id = $_GET['product_id'];		}	
	else if(isset($_POST['product_id'])) 	{	$product_id = $_POST['product_id'];		}
	
	$query_product = mysql_query("SELECT * FROM $database->products WHERE id='$product_id'");
	while($list_prodoct = mysql_fetch_assoc($query_product))
	{
		$product['id']				=	$list_prodoct['id'];
		$product['status']			=	$list_prodoct['status'];
		$product['code']			=	$list_prodoct['code'];
		$product['name']			=	$list_prodoct['name'];
		$product['quantity']		=	$list_prodoct['quantity'];
		$product['cost_price']		=	$list_prodoct['cost_price'];
		$product['sale_price']		=	$list_prodoct['sale_price'];
	}
	
	function get_the_product($value)
	{
		global $product;
		return $product[$value];
	}
	
	function the_product($value)
	{
		echo get_the_product($value);
	}
}


/* ----------------------------------------------
	PRODUCT
---------------------------------------------- */
function get_product($product_id, $value)
{
	global $database;
	
	$product_id = safety_filter($product_id);
	
	$query_product = mysql_query("SELECT * FROM $database->products WHERE id='$product_id'");
	while($list_prodoct = mysql_fetch_assoc($query_product))
	{
		return $list_prodoct[$value];
	}
}

function product($id, $value)
{
	echo get_product($product_id, $value);
}



/* ----------------------------------------------
	PRODUCT BOX
---------------------------------------------- */
function box_product_list($product_id, $product_code, $cost_price, $sale_price)
{
	global $database;
	
	echo '
	<div id="box_product_list" class="reveal-modal expand" style="width:800px;">
		<table class="dataTable">
			<thead>
				<tr>
					<th></th>
					<th>'.get_lang("Code").'</th>
					<th>'.get_lang("Name").'</th>
					<th class="text-right">'.get_lang("Cost Price").'</th>
					<th class="text-right">'.get_lang("Sale Price").'</th>
				</tr>
			</thead>
			<tbody>
			';
		
			$query_products	=	mysql_query("SELECT * FROM $database->products WHERE status='publish'");
			while($list_products = mysql_fetch_assoc($query_products))
			{
				$products['id']				= $list_products['id'];
				$products['status'] 		= $list_products['status'];
				$products['code'] 			= $list_products['code'];
				$products['name'] 			= $list_products['name'];
				$products['cost_price'] 	= $list_products['cost_price'];	
				$products['sale_price'] 	= $list_products['sale_price'];
				
				echo '
				<tr>
					<td width="1"></td>
					<td><a href="#" class="fnc close-reveal-modal" 
						onClick="product_select(\''.$products['id'].'\', \''.$products['code'].'\', \''.get_mf($products['cost_price']).'\', \''.get_mf($products['sale_price']).'\');">'.$products['code'].'</a></td>
					<td>'.$products['name'].'</td>
					<td class="text-right">'.get_mf($products['cost_price']).'</td>
					<td class="text-right">'.get_mf($products['sale_price']).'</td>
				</tr>
				';	
			}
		
			echo '
			</tbody>
		</table>
		<a class="x close-reveal-modal">&#215;</a>
	</div>';
	
	echo '
	<script>
		function product_select(id, code, cost_price, sale_price)
		{
			document.getElementById("'.$product_id.'").value = id;
			document.getElementById("'.$product_code.'").value = code;
			if("'.$cost_price.'" != ""){ document.getElementById("'.$cost_price.'").value = cost_price; }
			if("'.$sale_price.'" != ""){ document.getElementById("'.$sale_price.'").value = sale_price;	}
		}
	</script>
	';
}



/* ----------------------------------------------
	BOX PRODUCT CARD
---------------------------------------------- */
function box_product_card($product_id)
{
	global $database;
	
	$product_id = safety_filter($product_id);
	
	$query = mysql_query("SELECT * FROM $database->products WHERE id='$product_id'");
	while($list = mysql_fetch_assoc($query))
	{
		$product['id']				= $list['id'];
		$product['status'] 			= $list['status'];
		$product['code'] 			= $list['code'];
		$product['name'] 			= $list['name'];
		$product['quantity'] 		= $list['quantity'];	
		
	}
		
	echo '
	<div id="box_product_card_'.$product['id'].'" class="reveal-modal expand" style="width:800px;">
		<h3>'.get_lang('Product Card').'</h3>

		<div class="row">
			<div class="two columns"> <strong>'.get_lang('Product Code').'</strong> </div> <div class="ten columns">: '.$product['code'] .' </div>
		</div> <!-- /.row -->
		<div class="row">
			<div class="two columns"> <strong>'.get_lang('Product Name').'</strong> </div> <div class="ten columns">: '.$product['name'] .' </div>
		</div> <!-- /.row -->
		';
		?>
        <hr />
        <script>
		function popup_barcode_print()
		{
				window.open ('<?php echo url(''); ?>/include/class/barcode/barcode_show.php?barcode=<?php echo $product['code']; ?>&print='+ true +'','mywindow','menubar=0,resizable=0,width=10,height=10');	
		}
		</script>
		<div class="button-bar">
			<ul class="button-group">
            	<li><a href="<?php url('page'); ?>/products/product.php?product_id=<?php echo $product['id']; ?>" class="button small secondary"><?php lang('Product Card'); ?> &raquo;</a></li>
            	<li><a href="#" onClick="popup_barcode_print();" class="button small secondary"><?php lang('Print Barcode'); ?></a></li>
                <li><a href="<?php url('page'); ?>/logs/logs.php?product_id=<?php echo $product['id']; ?>" class="button secondary small"><?php lang('Logs'); ?></a></li>
			</ul>
		</div>
        <?php
		echo '
		<a class="x close-reveal-modal">&#215;</a>
	</div>
	';
}



/* ----------------------------------------------
	CALC PRODUCT AMOUNT
---------------------------------------------- */
function calc_product_amount($product_id)
{
	global $database;
	
	$product_id = safety_filter($product_id);
	
	$quantity = 0;
	
	$query_fiche_items = mysql_query("SELECT * FROM $database->fiche_items WHERE status='publish' AND product_id='$product_id'");
	while($list_fiche_items = mysql_fetch_assoc($query_fiche_items))
	{
		$type 			= 	$list_fiche_items['type'];
		$fiche_quantity	=	$list_fiche_items['quantity'];
		
		if($type == 'output')
		{
			$quantity = $quantity - $fiche_quantity;
		}
		else if($type == 'input')
		{
			$quantity = $quantity + $fiche_quantity;
		}
	}
	
	mysql_query("UPDATE $database->products SET quantity='$quantity' WHERE id='$product_id'");
	echo mysql_error();
}
?>