<?php include_once('../../header.php'); ?>

<div class="button-bar">
  <ul class="button-group">
    <li><a href="<?php url('page'); ?>/user/profile.php" class="button secondary"><?php lang('Profile'); ?></a></li>
    <li><a href="<?php url('page'); ?>/user/profile_change_ps.php" class="button secondary"><?php lang('Change Password'); ?></a></li>
  </ul>
</div>

<div class="row">
	<div class="four columns">
    
    	<?php
		if(isset($_POST['btn_change_password']))
		{
			$continue = true;
			$password		=	isset($_POST['password']) ? safety_filter($_POST['password']) : exit	;
			$password_again	=	isset($_POST['password_again']) ? safety_filter($_POST['password_again']) : exit	;
			$old_password	=	isset($_POST['old_password']) ? safety_filter($_POST['old_password']) : exit	;
			
			if(md5($old_password) != get_the_current_user('password'))	{	$continue = false;	alert_box('alert', get_lang('The old password is not correct'));	}
			if($password != $password_again)	{ $continue = false;	alert_box('alert', get_lang('Passwords do not match'));	}
			
			if($continue == true)
			{
				if(update_user(get_the_current_user('id'), get_the_current_user('status'), get_the_current_user('user_name'), $password, get_the_current_user('level')))
				{
					alert_box('success', get_lang('Successful'));	
				}
			}
		}
		?>
        
        <form name="form_login" id="form_login" action="" method="POST">
            <fieldset>
                <legend><?php lang('Profile'); ?></legend>
                
                
                
                <label for="user_name"><?php lang('User Name'); ?></label>
                <input type="text" name="user_name" id="user_name" class="required" minlength="3" maxlength="20" value="<?php the_current_user('user_name'); ?>" readonly / >    
                
                <div class="row">
                	<div class="six columns">
                    <label for="level"><?php lang('Level'); ?></label>
                    <select name="level" id="level">
                        <option value="<?php the_current_user('level'); ?>" selected><?php the_current_user('level'); ?></option>
                    </select>
                    </div>
                    <div class="six columns">
                    <label for="status"><?php lang('Status'); ?></label>
                    <select name="status" id="status">
                        <option value="<?php the_current_user('status'); ?>" selected><?php the_current_user('status'); ?></option>
                    </select>
                    </div>
                </div>
                
                <hr />
                
                <label for="password"><?php lang('New'); ?> <?php lang('Password'); ?></label>
                <input type="password" name="password" id="password" minlength="3" maxlength="20" class="required" />      
                
                <label for="password_again"><?php lang('New'); ?> <?php lang('Password Again'); ?></label>
                <input type="password" name="password_again" id="password_again" minlength="3" maxlength="20" class="required" />
                
                <label for="old_password"><?php lang('Old Password'); ?></label>
                <input type="password" name="old_password" id="old_password" minlength="3" maxlength="20" class="required" />     
                
                <p></p> 
                
                <input type="submit" name="btn_change_password" id="btn_change_password" class="button" value="<?php lang('Change Password'); ?>" />
                <input type="reset" class="button" value="<?php lang('Reset'); ?>" />
              
				<p></p>  
              
            </fieldset>
        </form>
    </div> <!-- /.four columns -->
    <div class="eight columns">
    
    	
    </div> <!-- /.eight columns -->
</div> <!-- /.row -->

<?php include_once('../../footer.php'); ?>