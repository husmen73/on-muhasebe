<?php include_once('header.php'); ?>

<?php change_navigation(get_lang('')); ?>
<?php $page_access = false; ?>


<div class="row">
	<div class="six columns">
    	<table class="dataTable">
        	<thead>
            	<tr>
                	<th width="1">&nbsp;</th>
                    <th><?php lang('Barcode Code'); ?></th>
                    <th><?php lang('Product Name'); ?></th>
                    <th><?php lang('Quantity'); ?></th>
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
						<td><a href="'.get_url('page').'/products/product.php?product_id='.$list_products['id'].'" class="link">'.$list_products['code'].'</a></td>
						<td>'.$list_products['name'].'</td>
						<td class="text-center">'.get_nf($list_products['quantity']).'</td>
					</tr>
					';
				}
				?>
            </tbody>
        </table>
    </div> <!-- /.six columns -->
	<div class="six columns">
        <table width="100%">
            <thead>
            	<tr>
                    <th class="text-center" colspan="4"><?php lang('Tasks from'); ?></th>
                </tr>
                <tr>
                    <th><?php lang('Sender'); ?></th>
                    <th><?php lang('Title'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
                $query_message = mysql_query("SELECT * FROM $database->message WHERE 
                status='publish' AND type='task' 
                AND inbox_id='".get_the_current_user('id')."'
                AND top_id='0'
				AND task_status='open'
                OR
                status='publish' AND type='task' 
                AND receiver_id='".get_the_current_user('id')."'
                AND top_id='0'
				AND task_status='open'
                ORDER BY last_date DESC LIMIT 10");
            
            while($list_message = mysql_fetch_assoc($query_message))
            {
                $last_sate = '';
                if($list_message['last_state'] == 'open' and get_the_current_user('id') == $list_message['inbox_id']) { $last_sate = 'style="text-decoration:underline; font-weight:bold;"';	}
                else if($list_message['last_state'] == 'close') { $last_sate = '';	}
                
                $username = get_user($list_message['sender_id'], 'display_name'); 
                
                echo '
                <tr>
                    <td '.$last_sate.'>'.substr($username, 0, 6).'</td>
                    <td '.$last_sate.'><a href="'.get_url('page').'/user/task.php?type=receiver&task_id='.$list_message['id'].'&refresh">'.substr($list_message['title'],0,30).'</a></td>
                </tr>
                ';	
            }
            ?>
            </tbody>
        </table>
    </div> <!-- /.six columns -->
</div> <!-- /.row -->



<?php include_once('footer.php'); ?>