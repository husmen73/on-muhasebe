<?php include_once('../../header.php'); ?>

<?php change_navigation(get_lang('Current Card')); ?>

<?php if(!isset($_GET['current_id'])){ alert_box('alert', get_lang('No Current ID number.')); exit;	} ?>

<script>
function popup_barcode_print()
{
		window.open ('<?php echo url(''); ?>/include/class/barcode/barcode_show.php?barcode=<?php the_current_card('code'); ?>&print='+ true +'','mywindow','menubar=0,resizable=0,width=10,height=10');	
}
</script>

<div class="row hide-on-print">
	<div class="eight columns">
		<div class="button-bar">
			<ul class="button-group">
            	<li><a href="#" onClick="popup_barcode_print();" class="button small secondary"><?php lang('Print Barcode'); ?></a></li>
                <li><a href="<?php url('page'); ?>/fiche/list.php?current_id=<?php the_current_card('id'); ?>" class="button small secondary"><?php lang('Fiche List'); ?></a></li>
            	<li><a href="<?php url('page'); ?>/logs/logs.php?current_id=<?php the_current_card('id'); ?>" class="button secondary small"><?php lang('Logs'); ?></a></li>
			</ul>
		</div>
    </div> <!-- /.eight columns -->
    <div class="four columns text-right">
 	<?php if(get_the_current_card('status') == 'publish') : ?>
    	<a href="?current_id=<?php the_current_card('id'); ?>&status=delete" class="button alert small"><?php lang('delete'); ?></a>
    <?php else : ?>
    	<a href="?current_id=<?php the_current_card('id'); ?>&status=publish" class="button success small"><?php lang('publish'); ?></a>
    <?php endif; ?>
    </div> <!-- /.four columns -->
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
	if($status == 'publish' or $status == 'delete'){} else { alert_box('alert', 'No Status'); exit; }
	
	mysql_query("UPDATE $database->currents SET status='$status' WHERE id='".get_the_current_card('id')."'");	
	
	go_to_page('?current_id='.get_the_current_card('id').'');
}


if(isset($_POST['btn_update']))
{
	$datetime	=	safety_filter($_POST['datetime']);
	$code		=	safety_filter($_POST['code']);
	$name		=	safety_filter($_POST['name']);
	
	if(update_current(get_the_current_card('id'), $name))
	{
		go_to_page('?current_id='.get_the_current_card('id').'&success=update');
	}
}
?>

<form name="form1" id="form1" action="?current_id=<?php the_current_card('id'); ?>" method="POST">
	<div class="row">
    	<div class="six columns">
            <fieldset>
                <legend><?php lang('Current'); ?></legend>

                <label for="code"><?php lang('Current Code'); ?></label>
                <input type="text" name="code" id="code" maxlength="20" minlength="3" class="required" value="<?php the_current_card('code'); ?>" readonly />

                <label for="name"><?php lang('Current Name'); ?></label>
                <input type="text" name="name" id="name" maxlength="20" minlength="3" class="required" value="<?php the_current_card('name'); ?>" />
                
                <h3><?php lang('Balance'); ?>: <span class="td-underline"><?php mf(get_the_current_card('balance')); ?></span></h3>
                	                    
            </fieldset>
            
        </div> <!-- /.six columns -->
        <div class="six columns">
        	<fieldset>
                <legend><?php lang('Barcode'); ?></legend>
        		<div class="text-center">
                	<img src="<?php url(''); ?>/include/class/barcode/barcode.php?barcode=<?php the_current_card('code'); ?>" style="border:1px solid #ccc; padding:4px;" />
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