<?php
/* ----------------------------------------------
	ADD CURRENT
---------------------------------------------- */
function add_current($code, $name)
{
	global $database;
	
	$code			=	safety_filter($code);
	$name			=	safety_filter($name);
	
	if(strlen($code) < 3)	{ alert_box('alert', get_lang('Not Applicable Product Code')); return false;	}
	if(strlen($name) < 3)	{ alert_box('alert', get_lang('Not Applicable Product Name')); return false;	}
	if(mysql_num_rows(mysql_query("SELECT * FROM $database->currents WHERE status='publish' AND code='$code'")) > 0)
		{	alert_box('alert', get_lang('This current code exists in the database.')); return false;	}
	
	mysql_query("INSERT INTO $database->currents
	(code, name, balance)
	VALUES
	('$code', '$name', '0.0000')");
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
	ADD CURRENT
---------------------------------------------- */
function update_current($id, $name)
{
	global $database;
	
	$id		=	safety_filter($id);
	$name 	=	safety_filter($name);
	
	if(strlen($name) < 3)	{ alert_box('alert', get_lang('Not Applicable Product Name')); return false;	}
	
	$update = mysql_query("UPDATE $database->currents SET name='$name' WHERE id='$id'");
	if(mysql_affected_rows() > 0)
	{ return true;	}
	else { if($update) { return false;	} else { return false;	}	}
		
}



/* ----------------------------------------------
	THE CURRENT
---------------------------------------------- */
if(isset($_GET['current_id']) or isset($_POST['current_id']))
{
	$current_id = 0;
	if(isset($_GET['current_id'])) 			{	$current_id = $_GET['current_id'];		}	
	else if(isset($_POST['current_id'])) 	{	$current_id = $_POST['current_id'];		}
	
	$query_current = mysql_query("SELECT * FROM $database->currents WHERE id='$current_id'");
	while($list_current = mysql_fetch_assoc($query_current))
	{
		$current['id']				=	$list_current['id'];
		$current['status']			=	$list_current['status'];
		$current['code']			=	$list_current['code'];
		$current['name']			=	$list_current['name'];
		$current['balance']			=	$list_current['balance'];
	}
	
	function get_the_current_card($value)
	{
		global $current;
		return $current[$value];
	}
	
	function the_current_card($value)
	{
		echo get_the_current_card($value);
	}
}




/* ----------------------------------------------
	CURRENT
---------------------------------------------- */
function get_current_card($current_id, $value)
{
	global $database;
	
	$current_id = safety_filter($current_id);
	
	$query_current = mysql_query("SELECT * FROM $database->currents WHERE id='$current_id'");
	while($list_current = mysql_fetch_assoc($query_current))
	{
		return $list_current[$value];
	}	
}

function current_card($current_id, $value)
{
	echo get_current_card($current_id, $value);
}



/* ----------------------------------------------
	PRODUCT BOX
---------------------------------------------- */
function box_current_list($current_id, $current_code)
{
	global $database;
	
	echo '
	<div id="box_current_list" class="reveal-modal expand" style="width:800px;">
		<h3>'.get_lang('Current List').'</h3>
		<table class="dataTable">
		<thead>
			<tr>
				<th width="1"></th>
				<th>'.get_lang("Current Code").'</th>
				<th>'.get_lang("Current Name").'</th>
				<th class="text-right">'.get_lang("Balance").'</th>
			</tr>
		</thead>
		<tbody>
		';
		
		$query_currents		=	mysql_query("SELECT * FROM $database->currents WHERE status='publish'");
		while($list_currents = mysql_fetch_assoc($query_currents))
		{
			$currents['id']				= $list_currents['id'];
			$currents['status'] 		= $list_currents['status'];
			$currents['code'] 			= $list_currents['code'];
			$currents['name'] 			= $list_currents['name'];
			$currents['balance'] 		= $list_currents['balance'];	
			
			echo '
			<tr>
				<td></td>
				<td><a href="#" class="fnc close-reveal-modal" onClick="customer_select(\''.$currents['id'].'\', \''.$currents['code'].'\');">'.$currents['code'].'</a></td>
				<td>'.$currents['name'].'</td>
				<td class="text-right">'.get_mf($currents['balance']).'</td>
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
		function customer_select(id, code)
		{
			document.getElementById("'.$current_id.'").value = id;
			document.getElementById("'.$current_code.'").value = code;
		}
	</script>
	';
}



/* ----------------------------------------------
	BOX CURRENT CARD
---------------------------------------------- */
function box_current_card($current_id)
{
	global $database;
	
	$current_id = safety_filter($current_id);
	
	$query_current = mysql_query("SELECT * FROM $database->currents WHERE id='$current_id'");
	while($list_current = mysql_fetch_assoc($query_current))
	{
		$current['id']				= $list_current['id'];
		$current['status'] 			= $list_current['status'];
		$current['code'] 			= $list_current['code'];
		$current['name'] 			= $list_current['name'];
		$current['balance'] 		= $list_current['balance'];	
		
	}
		
	echo '
	<div id="box_current_card_'.$current_id.'" class="reveal-modal expand" style="width:800px;">
		<h3>'.get_lang('Current Card').'</h3>

		<div class="row">
			<div class="two columns"> <strong>'.get_lang('Current Code').'</strong> </div> <div class="ten columns">: '.$current['code'] .' </div>
		</div> <!-- /.row -->
		<div class="row">
			<div class="two columns"> <strong>'.get_lang('Current Name').'</strong> </div> <div class="ten columns">: '.$current['name'] .' </div>
		</div> <!-- /.row -->
		';
		?>
        <hr />
        <script>
		function popup_barcode_print()
		{
				window.open ('<?php echo url(''); ?>/include/class/barcode/barcode_show.php?barcode=<?php echo $current['code']; ?>&print='+ true +'','mywindow','menubar=0,resizable=0,width=10,height=10');	
		}
		</script>
		<div class="button-bar">
			<ul class="button-group">
            	<li><a href="<?php url('page'); ?>/currents/current.php?current_id=<?php echo $current['id']; ?>" class="button small secondary"><?php lang('Current Card'); ?> &raquo;</a></li>
            	<li><a href="#" onClick="popup_barcode_print();" class="button small secondary"><?php lang('Print Barcode'); ?></a></li>
                <li><a href="<?php url('page'); ?>/logs/logs.php?current_id=<?php echo $current['id']; ?>" class="button secondary small"><?php lang('Logs'); ?></a></li>
			</ul>
		</div>
        <?php
		echo '
		<a class="x close-reveal-modal">&#215;</a>
	</div>
	';
}



/* ----------------------------------------------
	CALC CURRENT BALANCE
---------------------------------------------- */
function calc_current_balance($current_id)
{
	global $database;
	
	$current_id = safety_filter($current_id);
	
	$balance = 0;
	
	$query_fiche = mysql_query("SELECT * FROM $database->fiche WHERE status='publish' AND current_id='$current_id'");
	while($list_fiche = mysql_fetch_assoc($query_fiche))
	{
		$type 			= 	$list_fiche['type'];
		$grand_total	=	$list_fiche['grand_total'];
		
		if($type == 'output')
		{
			$balance = $balance + $grand_total;
		}
		else if($type == 'input')
		{
			$balance = $balance - $grand_total;
		}
	}
	
	mysql_query("UPDATE $database->currents SET balance='$balance' WHERE id='$current_id'");
}
?>