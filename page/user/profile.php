<?php include_once('../../header.php'); ?>

<div class="button-bar">
  <ul class="button-group">
    <li><a href="<?php url('page'); ?>/user/profile.php" class="button secondary"><?php lang('Profile'); ?></a></li>
    <li><a href="<?php url('page'); ?>/user/profile_change_ps.php" class="button secondary"><?php lang('Change Password'); ?></a></li>
  </ul>
</div>

<div class="row">
    <div class="four columns end">
    
    	<?php
		if(isset($_POST['btn_change_meta']))
		{
			$display_name		=	isset($_POST['display_name']) ? safety_filter($_POST['display_name']) : exit	;
			
			mysql_query("UPDATE $database->users SET
			display_name='$display_name'
			WHERE
			id='$user_id'
			");
			if(mysql_affected_rows() > 0)
			{
				alert_box('success', get_lang('Successful'));
				echo '<script> window.location = "profile.php"; </script>';		
			}
		}
		?>
        
        <form name="form_user_meta" id="form_user_meta" action="" method="POST">
            <fieldset>
                <legend><?php lang('User Meta'); ?></legend>
                
                <label for="user_name"><?php lang('Name/Surname'); ?></label>
                <input type="text" name="display_name" id="display_name" class="" maxlength="20" value="<?php the_current_user('display_name'); ?>" / >    
                
                
                
                <input type="submit" name="btn_change_meta" id="btn_change_meta" class="button" value="<?php lang('Update'); ?>" />
                <input type="reset" class="button" value="<?php lang('Reset'); ?>" />
              
				<p></p>  
              
            </fieldset>
        </form>
    </div> <!-- /.four columns -->
    <div class="eight columns">
    
    	
    </div> <!-- /.eight columns -->
</div> <!-- /.row -->

<?php include_once('../../footer.php'); ?>