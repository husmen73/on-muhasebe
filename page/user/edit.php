<?php include_once('../../header.php'); ?>
<?php if(! isset($_GET['user_id'])) { alert_box('alert', get_lang('No ID'));	exit;	} ?>
<div class="row">
	<div class="four columns">
    
    	<?php
		if(isset($_POST['btn_update_user']))
		{
			$continue = true;
			$status			=	isset($_POST['status']) ? safety_filter($_POST['status']) : exit	;
			$user_name		=	isset($_POST['user_name']) ? safety_filter($_POST['user_name']) : exit	;
			$password		=	isset($_POST['password']) ? safety_filter($_POST['password']) : exit	;
			$password_again	=	isset($_POST['password_again']) ? safety_filter($_POST['password_again']) : exit	;
			$level			=	isset($_POST['level']) ? safety_filter($_POST['level']) : exit	;
			
			if($password != '')
			{
				if($password != $password_again)	{ $continue = false;	alert_box('alert', get_lang('Passwords do not match'));	}
			}
			
			if($continue == true)
			{
				if(update_user(get_the_user('id'), $status, $user_name, $password, $level))
				{
					echo '<script> window.location = "?user_id='.get_the_user('id').'&success"; </script>';	
				}
			}
		}
		
		if(isset($_GET['success']))
		{
			alert_box('success', get_lang('Successful'));
		}
		?>
        
        <form name="form_login" id="form_login" action="?user_id=<?php the_user('id'); ?>" method="POST">
            <fieldset>
                <legend><?php lang('Update User'); ?></legend>
                
                
                
                <label for="user_name"><?php lang('User Name'); ?></label>
                <input type="text" name="user_name" id="user_name" class="required" minlength="3" maxlength="20" value="<?php the_user('user_name'); ?>" />
                
                <label for="password"><?php lang('New'); ?> <?php lang('Password'); ?></label>
                <input type="password" name="password" id="password" class=""  minlength="3" maxlength="20" />      
                
                <label for="password_again"><?php lang('New'); ?> <?php lang('Password Again'); ?></label>
                <input type="password" name="password_again" id="password_again" class=""  minlength="3" maxlength="20" />          
                
                <div class="row">
                	<div class="six columns">
                    <label for="level"><?php lang('Level'); ?></label>
                    <select name="level" id="level">
                        <option value="1" <?php if(get_the_user('level') == '1') { echo 'selected';	}	?>>1</option>
                        <option value="2" <?php if(get_the_user('level') == '2') { echo 'selected';	}	?>>2</option>
                        <option value="3" <?php if(get_the_user('level') == '3') { echo 'selected';	}	?>>3</option>
                        <option value="4" <?php if(get_the_user('level') == '4') { echo 'selected';	}	?>>4</option>
                        <option value="5" <?php if(get_the_user('level') == '5') { echo 'selected';	}	?>>5</option>
                    </select>
                    </div>
                    <div class="six columns">
                    <label for="status"><?php lang('Status'); ?></label>
                    <select name="status" id="status">
                        <option value="publish" <?php if(get_the_user('status') == 'publish') { echo 'selected';	}	?>><?php lang('publish'); ?></option>
                        <option value="delete" <?php if(get_the_user('status') == 'delete') { echo 'selected';	}	?>><?php lang('delete'); ?></option>
                    </select>
                    </div>
                </div>
                
                
                <p></p> 
                
                <input type="submit" name="btn_update_user" id="btn_update_user" class="button" value="<?php lang('Update'); ?>" />
                <input type="reset" class="button" value="<?php lang('Reset'); ?>" />
              
				<p></p>  
              
            </fieldset>
        </form>
    </div> <!-- /.four columns -->
    <div class="eight columns">
    
    	
    </div> <!-- /.eight columns -->
</div> <!-- /.row -->

<?php include_once('../../footer.php'); ?>