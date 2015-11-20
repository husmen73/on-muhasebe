<?php include_once('../../header.php'); ?>

<?php change_navigation(get_lang('Current List')); ?>

<div class="row">
	<div class="twelve columns">
    	<table class="dataTable">
        	<thead>
            	<tr>
                	<th width="1">&nbsp;</th>
                    <th><?php lang('Barcode Code'); ?></th>
                    <th><?php lang('Current Name'); ?></th>
                    <th><?php lang('Balance'); ?></th>
                </tr>
            </thead>
            <tbody>
            	<?php
				$query_currents = mysql_query("SELECT * FROM $database->currents WHERE status='publish'");
				while($list_currents = mysql_fetch_assoc($query_currents))
				{
					echo '
					<tr>
						<td></td>
						<td><a href="current.php?current_id='.$list_currents['id'].'" class="link">'.$list_currents['code'].'</a></td>
						<td>'.$list_currents['name'].'</td>
						<td class="text-center">'.$list_currents['balance'].'</td>
					</tr>
					';
				}
				?>
            </tbody>
        </table>
    </div> <!-- /.twelve columns -->
</div> <!-- /.row -->

<?php include_once('../../footer.php'); ?>