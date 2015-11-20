<?php include_once('../../header.php'); ?>
<?php
if( get_the_current_user('level') != 1 )
{
	alert_box('alert', get_lang('Do not have permission to access this page'));
	exit;
}
?>
<div class="row">
	<div class="four columns">
    
    	<?php
		if(isset($_POST['btn_add_user']))
		{
			$continue = true;
			$user_name		=	isset($_POST['user_name']) ? safety_filter($_POST['user_name']) : exit	;
			$password		=	isset($_POST['password']) ? safety_filter($_POST['password']) : exit	;
			$password_again	=	isset($_POST['password_again']) ? safety_filter($_POST['password_again']) : exit	;
			$level			=	isset($_POST['level']) ? safety_filter($_POST['level']) : exit	;
			
			if(add_user($user_name, $password, $password_again, $level))
			{
				alert_box('success', get_lang('Was added to the user'));	
			}
		}
		?>
        
        <form name="form_login" id="form_login" action="?" method="POST">
            <fieldset>
                <legend><?php lang('New User'); ?></legend>
                
                <label for="user_name"><?php lang('User Name'); ?></label>
                <input type="text" name="user_name" id="user_name" class="required" minlength="3" maxlength="20" />
                
                <label for="password"><?php lang('Password'); ?></label>
                <input type="password" name="password" id="password" class="required"  minlength="3" maxlength="20" />      
                
                <label for="password_again"><?php lang('Password Again'); ?></label>
                <input type="password" name="password_again" id="password_again" class="required"  minlength="3" maxlength="20" />          
                
                <label for="level"><?php lang('Level'); ?></label>
                <select name="level" id="level">
                	<option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                
                <p></p> 
                
                <input type="submit" name="btn_add_user" id="btn_add_user" class="button" value="<?php lang('Add'); ?>" />
                <input type="reset" class="button" value="<?php lang('Reset'); ?>" />
              
				<p></p>  
              
            </fieldset>
        </form>
    </div> <!-- /.four columns -->
    <div class="eight columns">
    
    	<?php
		if(isset($_GET['delete_user_id']))
		{
			if($_GET['delete_user_id'] == 1)
			{
				alert_box('alert', get_lang('Executive No. 1 be deleted'));
			}
			else
			{
				if(delete_user($_GET['delete_user_id']))
				{
					alert_box('secondary', get_lang('User account deleted'));	
				}
			}
		}
		?>
        
        <table class="dataTable" width="100%">
            <thead>
                <tr>
                    <th width="1"></th>
                    <th width="20"></th>
                    <th width="20"></th>
                    <th><?php lang('User Name'); ?></th>
                    <th><?php lang('Level'); ?></th>
                </tr>
            </thead>
            <tbody>
			<?php
            $query_users = mysql_query("SELECT * FROM $database->users WHERE status='publish' ORDER BY id ASC");
            while($list_users = mysql_fetch_assoc($query_users))
            {
				echo '
				<tr>
					<td></td>
					<td>
						<a href="edit.php?user_id='.$list_users['id'].'" title="'.get_lang('Edit').'" class="icon-hover"><img src="'.get_url('theme/images').'/icon/12/edit.png" /></a>
					</td>
					<td>
						<a href="?delete_user_id='.$list_users['id'].'" title="'.get_lang('Delete').'" class="icon-hover"><img src="'.get_url('theme/images').'/icon/12/delete.png" /></a>
					</td>
					<td>'.$list_users['user_name'].'</td>
					<td>'.$list_users['level'].'</td>
				</tr>
				';
            }
            ?>
            </tbody>
        </table>
    </div> <!-- /.eight columns -->
</div> <!-- /.row -->

<?php include_once('../../footer.php'); ?>