<?php include_once('../../header.php'); ?>

<?php change_navigation(get_lang('Product List')); ?>

<div class="row">
	<div class="twelve columns">
    	<table class="dataTable">
        	<thead>
            	<tr>
                	<th width="1">&nbsp;</th>
                    <th><?php lang('Barcode Code'); ?></th>
                    <th><?php lang('Product Name'); ?></th>
                    <th><?php lang('Quantity'); ?></th>
                    <th><?php lang('Cost Price'); ?></th>
                    <th><?php lang('Sale Price'); ?></th>
                </tr>
            </thead>
            <tbody>
            	<?php
				$query_products = mysql_query("SELECT * FROM $database->products WHERE status='publish'");
				while($list_products = mysql_fetch_assoc($query_products))
				{
					echo '
					<tr>
						<td></td>
						<td><a href="product.php?product_id='.$list_products['id'].'" class="link">'.$list_products['code'].'</a></td>
						<td>'.$list_products['name'].'</td>
						<td class="text-center">'.$list_products['quantity'].'</td>
						<td class="text-right">'.get_mf($list_products['cost_price']).'</td>
						<td class="text-right">'.get_mf($list_products['sale_price']).'</td>
					</tr>
					';
				}
				?>
            </tbody>
        </table>
    </div> <!-- /.twelve columns -->
</div> <!-- /.row -->

<?php include_once('../../footer.php'); ?>