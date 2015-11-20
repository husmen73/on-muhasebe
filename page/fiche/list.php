<?php include_once('../../header.php'); ?>

<?php change_navigation(get_lang('Fiche List')); ?>

<?php
if(isset($_GET['current_id']))
{
	$query_fiche = mysql_query("SELECT * FROM $database->fiche WHERE status='publish' AND current_id='".safety_filter($_GET['current_id'])."' ORDER BY id DESC");
	box_current_card($_GET['current_id']);
	echo '<h4>'.get_lang('Current Code').': <a href="#" data-reveal-id="box_current_card_'.$_GET['current_id'].'" class="td-underline">'.get_current_card($_GET['current_id'], 'code').'</a></h4>';
}
else
{
	$query_fiche = mysql_query("SELECT * FROM $database->fiche WHERE status='publish' ORDER BY id DESC");
}
?>

<table class="dataTable">
	<thead>
    	<tr>
        	<th width="1"></th>
            <th><?php lang('ID'); ?></th>
            <th><?php lang('Type'); ?></th>
            <th><?php lang('Date'); ?></th>
            <th><?php lang('Current Code'); ?></th>
            <th><?php lang('Grand Total'); ?></th>
            <th><?php lang('Fiche Items'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
	
	while($list_fiche = mysql_fetch_assoc($query_fiche))
	{
		
		echo '
		<tr>
			<td width="1"></td>
			<td> <a href="fiche.php?fiche_id='.$list_fiche['id'].'" class="link">&raquo; '.$list_fiche['id'].'</a> </td>
			<td>'.get_lang($list_fiche['type']).'</td>
			<td>'.$list_fiche['date'].'</td>
			<td>'.get_current_card($list_fiche['current_id'], 'code').'</td>
			<td class="text-right">'.get_mf($list_fiche['grand_total']).'</td>
			<td class="text-center">'.$list_fiche['fiche_items'].'</td>
		</tr>
		';
	}
	?>
    </tbody>
</table>

<?php include_once('../../footer.php'); ?>