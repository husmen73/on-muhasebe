<?php include_once('../../header.php'); ?>
<?php $page_access = false;	?>

<!-- PAGE ACCESS FOR ADMIN CONTROL -->
<div class="row">
	<div class="twelve columns">
    	<?php if(get_the_current_user('level') != 1) : ?>
        	<?php alert_box('alert', get_lang('Do not have permission to access this page')); ?>
        <?php exit; endif; ?>
    </div> <!-- /.twelve columns -->
</div> <!-- /.row -->


<div class="row">
	<div class="twelve columns">
    	<?php
		/* ----- SUCCESS ----- */
		
		
		
		/* ----- UPDATE LANGUAGE ----- */
		if(isset($_POST['btn_update_language']))
		{
			$language = safety_filter($_POST['language']);
			if(update_meta('', '', 'settings', 'language', $language))
			{
				echo '<script> window.location = "settings.php?success"; </script>';	
			}
		}
		?>
    </div> <!-- /.twelve columns -->
</div> <!-- /.row -->

<div class="row">
	<div class="four columns">
    	<form name="form_language" id="form_language" action="" method="POST">
        	<fieldset>
            	<legend><?php lang('Language'); ?></legend>
                
                <label for="language"><?php lang('Language'); ?></label>
                <select name="language" id="language">
                	<option value="english.php" <?php if(get_meta('', '', 'settings', 'language') == 'english.php') {echo 'selected';} ?>>english.php</option>
                    <option value="turkish.php" <?php if(get_meta('', '', 'settings', 'language') == 'turkish.php') {echo 'selected';} ?>>turkish.php</option>
                    <option value="spanish.php" <?php if(get_meta('', '', 'settings', 'language') == 'spanish.php') {echo 'selected';} ?>>spanish.php</option>
                </select>
                
                <input type="submit" name="btn_update_language" id="btn_update_language" value="<?php lang('Update'); ?>" class="button" />
                
                <p></p>
            </fieldset>
        </form>
    </div> <!-- /.twelve columns -->
</div> <!-- /.row -->


<?php include_once('../../footer.php'); ?>