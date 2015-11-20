<div class="h20"></div>
<?php

?>
<div>
<div class="h20"></div>
<small class="pull-left">{elapsed_time} <?php lang('installed in seconds.'); ?></small>
<?php
if(isset($_POST['page_access_change']) and get_the_current_user('role') <= 2)
{
	$role = $_POST['role'];
	$page_name = $_POST['page_name'];
	
	update_option(array('option_group'=>'page_access', 'option_key'=>$page_name, 'option_value'=>$role));
	
	add_log(array('type'=>'page_access', 'title'=>get_lang('Page Access'), 'description'=>get_lang('Changed the access settings page').' ['.$page_name.'/'.$role.']'));
}
function control_page()
{
	$ci =& get_instance();
	$page_name = $ci->uri->segment(1).'/'.$ci->uri->segment(2);
	
	$data['/'] = '';
	$data['user/logout'] = '';
	$data['user/profile'] = '1';
	$data['user/new_message'] = '';
	$data['user/inbox'] = '';
	$data['user/outbox'] = '';
	$data['user/new_task'] = '';
	$data['user/task'] = '';
	$data['user/outbound_tasks'] = '';
	$data['general/about'] = '';
	
	if(isset($data[$page_name]))
	{
		?>
        <?php if(get_the_current_user('role') <= 2): ?>
		<small class="pull-right"><?php echo get_lang('access privileges for this page is fixed.'); ?></small>
        <?php endif; ?>
		<?php
    }
	else
	{
		$role = get_option(array('option_group'=>'page_access', 'option_key'=>$page_name));
		?>
          <!-- Modal -->
          <div class="modal fade" id="modal_pageAccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title"><?php lang('Page Access'); ?></h4>
                </div>
                <div class="modal-body">
                 <form name="form_page_access" id="form_page_access" action="" method="POST">
                    <label for="role" class="control-label ff-1 fs-16"><?php lang('Role'); ?></label>
                    <select name="role" id="role" class="form-control input-lg">
                      <option value="5" <?php if($role['option_value']==5){echo'selected';} ?>>Personel</option>
                      <option value="4" <?php if($role['option_value']==4){echo'selected';} ?>>Yetikili Personel</option>
                      <option value="3" <?php if($role['option_value']==3){echo'selected';} ?>>Birim Amiri</option>
                      <option value="2" <?php if($role['option_value']==2){echo'selected';} ?>>Yönetici</option>
                      <option value="1" <?php if($role['option_value']==1){echo'selected';} ?>>Süper Yönetici</option>
                    </select>
                    <input type="hidden" name="page_name" id="page_name" value="<?php echo $page_name; ?>" />
                    <input type="hidden" name="page_access_change" id="page_access_change" />
                 </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php lang('Close'); ?></button>
                  <button type="button" class="btn btn-primary" onclick="document.getElementById('form_page_access').submit();"><?php lang('Save'); ?></button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
     
        <!-- Button trigger modal -->

              
        <?php
		
		if($role)
		{
			if($role['option_value'] < get_the_current_user('role'))
			{
				redirect(site_url('user/no_access/'.$role['option_value']));	
			}
			?>
            
            <?php if(get_the_current_user('role') <= 2): ?>
            <small class="pull-right">
                <?php lang('this page'); ?> <strong><?php echo get_role_name($role['option_value']); ?></strong> <?php lang('and greater access to users.'); ?> 
                [<a data-toggle="modal" href="#modal_pageAccess">değiştir</a>]
            </small>
            <?php endif; ?>
            <?php
		}
		else
		{
			?>
            <?php if(get_the_current_user('role') <= 2): ?>
            <small class="pull-right">
                <?php lang('anyone can access this page.'); ?> [<a data-toggle="modal" href="#modal_pageAccess">değiştir</a>]
            </small>
            <?php endif; ?>
            <?php
		}
	}
	

}
control_page();
?>
</div>


</div> <!-- /.container -->
</body>
</html>