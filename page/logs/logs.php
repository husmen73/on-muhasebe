<?php include_once('../../header.php'); ?>

<?php change_navigation(get_lang('Log')); ?>

<?php
if(isset($_GET['fiche_id'])) 
{ 
	$query_logs = mysql_query("SELECT * FROM $database->logs WHERE fiche_id='".safety_filter($_GET['fiche_id'])."'");	
	echo '<h4>'.get_lang('Fiche ID').': '.$_GET['fiche_id'].'</h4>';
}
else if(isset($_GET['current_id']))
{
	$query_logs = mysql_query("SELECT * FROM $database->logs WHERE current_id='".safety_filter($_GET['current_id'])."'");
	box_current_card($_GET['current_id']);	
	echo '<h4>'.get_lang('Current Code').': <a href="#" data-reveal-id="box_current_card_'.$_GET['current_id'].'" class="td-underline">'.get_current_card($_GET['current_id'], 'code').'</a></h4>';
}
else if(isset($_GET['product_id']))
{
	$query_logs = mysql_query("SELECT * FROM $database->logs WHERE product_id='".safety_filter($_GET['product_id'])."'");
	box_product_card($_GET['product_id']);	
	echo '<h4>'.get_lang('Product Code').': <a href="#" data-reveal-id="box_product_card_'.$_GET['product_id'].'" class="td-underline">'.get_product($_GET['product_id'], 'code').'</a></h4>';
}
else
{
	
}
?>


<table class="dataTable">
	<thead>
    	<tr>
        	<th width="1"></th>
            <th><?php lang('Date'); ?></th>
            <th><?php lang('User'); ?></th>
            <th><?php lang('Current Code'); ?></th>
            <th><?php lang('Product Code'); ?></th>
            <th><?php lang('Fiche ID'); ?></th>
            <th><?php lang('Description'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
	while($list_logs = mysql_fetch_assoc($query_logs))
	{

		echo '
		<tr>
			<td width="1"></td>
			<td>'.$list_logs['date'].'</td>
			<td>'.get_user($list_logs['user_id'], 'display_name').'</td>
			<td>'.get_current_card($list_logs['current_id'], 'code').'</td>
			<td>'.get_product($list_logs['product_id'], 'code').'</td>
			<td class="text-center"> <a href="../fiche/fiche.php?fiche_id='.$list_logs['fiche_id'].'" target="_blank" class="link">'.$list_logs['fiche_id'].'</a> </td>
			<td>'.get_lang($list_logs['description']).'</td>
		</tr>
		';
	}
	?>
    </tbody>
</table>

<?php include_once('../../footer.php'); ?>