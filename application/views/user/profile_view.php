<?php
if(empty($user_id)){ $user_id = get_the_current_user('id'); }
?>

<?php $user = get_user(array('id'=>$user_id)); ?>
<legend id="page_title" class="ff-1 danger"><?php echo $user['display_name']; ?></legend>

<div class="row">
<div class="col-md-8">


<?php if($user['status'] == 0): ?>
	<?php alertbox('alert-warning', get_lang('This user has been deleted.'), '', false); ?>
<?php endif; ?>


<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="#profile" data-toggle="tab"><?php lang('Profile'); ?></a></li>
    <li><a href="#history" data-toggle="tab"><?php lang('History'); ?></a></li>
    <li class="dropdown">
        <a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown"><?php lang('Other'); ?> <b class="caret"></b></a>
        <ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
            <li><a href="<?php echo site_url('user/new_message/?user_id='.$user['id']); ?>"><span class="glyphicon glyphicon-envelope mr9"></span><?php lang('New Message'); ?></a></li>
            <li><a href="<?php echo site_url('user/new_task/?user_id='.$user['id']); ?>"><span class="glyphicon glyphicon-globe mr9"></span><?php lang('New Task'); ?></a></li>
        </ul>
    </li>
</ul>


<div id="myTabContent" class="tab-content">

	<!-- profile -->
	<div class="tab-pane fade in active" id="profile">
    	<div class="h20"></div>
		<?php
		
		
		
        if(isset($_POST['update']) and $user['id'] == get_the_current_user('id'))
        {
            $continue = true;
            $this->form_validation->set_rules('email', get_lang('E-mail'), 'required|min_length[3]|max_length[50]');
            $this->form_validation->set_rules('name', get_lang('Name'), 'required|min_length[2]|max_length[20]');
            $this->form_validation->set_rules('surname', get_lang('Surname'), 'required|min_length[2]|max_length[20]');
            $this->form_validation->set_rules('password', get_lang('Password'), 'min_length[4]|max_length[32]');
        
            if ($this->form_validation->run() == FALSE)
            {
                alertbox('alert-danger', '', validation_errors());
            }
            else
            {
                $data['email'] = $this->input->post('email');
                $data['name'] = $this->input->post('name');
                $data['surname'] = $this->input->post('surname');
                if($_POST['password'] != ''){ $data['password'] = md5($this->input->post('password')); }
                $data['role'] = $this->input->post('role');
                
                // Have barcode?
                $this->db->where('status', '1');
                $this->db->where('email', $data['email']);
                $this->db->where_not_in('id', $user['id']);
                $query = $this->db->get('users')->result_array();
                if($query)
                {
                    alertbox('alert-danger', get_lang('E-mail address is registered.'));
                    $continue = false;
                }
                
            
                if($continue)
                {
                    if(update_user($user['id'], $data))
                    {
                        alertbox('alert-success', get_lang('Operation is Successful'), '');	
                        $user = get_user(array('id'=>$user_id));
                        ?>
                        <script>$(document).ready(function(){$('#page_title').html('<?php echo $user['display_name']; ?>'); });</script>
                        <?php
						
						$log['type'] = 'profile';
						$log['other_id'] = 'user:'.$user['id'];
						$log['title'] = get_lang('Profile Update');
						$log['description'] = get_lang('Profile Update');
						add_log($log);
                    }
                }
            }
        }
        ?>
        
        <form name="form_new_product" id="form_new_product" action="" method="POST" class="validation">
            <div class="row">
                <div class="col-md-6">
                       
                    <div class="form-group">
                        <label for="email" class="control-label ff-1 fs-16"><?php lang('E-mail'); ?></label>
                        <div class="input-prepend input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                            <input type="text" id="email" name="email" class="form-control input-lg ff-1 required email" placeholder="<?php lang('E-mail'); ?>" minlength="3" maxlength="50" value="<?php echo $user['email']; ?>" readonly="readonly" />
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group">
                        <label for="password" class="control-label ff-1 fs-16"><?php lang('Password'); ?></label>
                        <div class="input-prepend input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-sound-7-1"></span></span>
                            <input type="password" id="password" name="password" class="form-control input-lg ff-1" placeholder="<?php lang('Password'); ?>" minlength="4" maxlength="32" value="" <?php if(strstr($_SERVER['SERVER_NAME'], 'tilpark.com')){echo 'readonly="readonly"';}; ?>>
                        </div>
                    </div> <!-- /.form-group -->
             
                </div> <!-- /.col-md-6 -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role" class="control-label ff-1 fs-16"><?php lang('Role'); ?></label>
                        <select name="role" id="role" class="form-control input-lg">
                          <?php if($user['role']==5): ?><option value="5"><?php lang('Staff'); ?></option><?php endif; ?>
                          <?php if($user['role']==4): ?><option value="4"><?php lang('Authorized Personnel'); ?></option><?php endif; ?>
                          <?php if($user['role']==3): ?><option value="3"><?php lang('Cheif'); ?></option><?php endif; ?>
                          <?php if($user['role']==2): ?><option value="2"><?php lang('Admin'); ?></option><?php endif; ?>
                          <?php if($user['role']==1): ?><option value="1"><?php lang('Super Admin'); ?></option><?php endif; ?>
                        </select>
                    </div> <!-- /.form-group -->
                    
                    <div class="form-group">
                        <label for="name" class="control-label ff-1 fs-16"><?php lang('Name'); ?></label>
                        <div class="input-prepend input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                            <input type="text" id="name" name="name" class="form-control input-lg ff-1 required" placeholder="<?php lang('Name'); ?>" minlength="2" maxlength="20" value="<?php echo $user['name']; ?>">
                        </div>
                    </div> <!-- /.form-group -->
                    
                    <div class="form-group">
                        <label for="surname" class="control-label ff-1 fs-16"><?php lang('Surname'); ?></label>
                        <div class="input-prepend input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                            <input type="text" id="surname" name="surname" class="form-control input-lg ff-1 required" placeholder="<?php lang('Surname'); ?>" minlength="2" maxlength="20" value="<?php echo $user['surname']; ?>">
                        </div>
                    </div> <!-- /.form-group -->
                   
                </div> <!-- /.col-md-6 -->
            </div> <!-- /.row -->
        
            <div class="text-right">
                <input type="hidden" name="update" />
                <?php if($user['id'] == get_the_current_user('id')): ?>
                <button class="btn btn-success btn-lg"><?php lang('Update'); ?> &raquo;</button>
                <?php endif; ?>
            </div> <!-- /.text-right -->
        </form>
	</div> <!-- /#profile -->
    <!-- /profile -->
    
    
    
    <!-- logs -->
	<div class="tab-pane fade" id="history">
        <div class="h20"></div>
        <?php $log['user_id'] = $user_id; ?>
        <?php get_log_table($log, 'DESC', array('user'=>false)); ?> 
	</div> <!-- #history -->
    <!-- /logs -->
       
       
</div> <!-- /.tab-content -->





</div> <!-- /.col-md-8 -->
<div class="col-md-4">
	<div style="height:10px;"></div>
    <form name="form_photo_upload" id="form_photo_upload" action="" method="post" enctype="multipart/form-data" style="border-left:1px solid #ccc; padding-left:10px;">
		<legend><?php lang('Profile Photo'); ?></legend>
        
        
        <?php
		// avatar upload
		if(isset($_FILES['avatar']) and $user['id'] == get_the_current_user('id'))
		{
			$avatar = $_FILES['avatar'];
			
			// calc bayt = megabayt
			function formatBytes($size, $precision = 1) 
			{ 
				$new_size = $size / 1024;
				$new_size = $new_size / 1024;
				return round($new_size,3);
				
			} 
			$file_size = formatBytes($_FILES["avatar"]["size"]);

				
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$temp = explode(".", $_FILES["avatar"]["name"]);
			$extension = end($temp);
			if ((($_FILES["avatar"]["type"] == "image/gif")
			|| ($_FILES["avatar"]["type"] == "image/jpeg")
			|| ($_FILES["avatar"]["type"] == "image/jpg")
			|| ($_FILES["avatar"]["type"] == "image/pjpeg")
			|| ($_FILES["avatar"]["type"] == "image/x-png")
			|| ($_FILES["avatar"]["type"] == "image/png"))
			&& ($file_size < 2)
			&& in_array($extension, $allowedExts))
			{
				if ($_FILES["avatar"]["error"] > 0)
				{
					echo "Return Code: " . $_FILES["avatar"]["error"] . "<br>";
				}
				else
				{
					// eger /user klasoru yok ise olustur
					if(!file_exists("uploads")){ mkdir("uploads"); }
					
					if(!file_exists("uploads/avatar")){ mkdir("uploads/avatar"); }
					
					move_uploaded_file($_FILES["avatar"]["tmp_name"],'uploads/avatar/'.$user['id'].'.jpg');
					
					$this->db->where('id', $user['id']);
					$this->db->update('users', array('avatar'=>'uploads/avatar/'.$user['id'].'.jpg'));
									
					$user = get_user(array('id'=>$user_id));
 
					alertbox('alert-success', get_lang('Profile picture was uploaded successfully.'));
					
					$log['type'] = 'profile';
					$log['other_id'] = 'user:'.$user['id'];
					$log['title'] = get_lang('Profile Photo');
					$log['description'] = get_lang('Profile Update');
					add_log($log);
					
				}
			}
			else
			{
				alertbox('alert-danger', '<h4>Bilinmeyen bir hata oluştu.</h4>
				<ul>
					<li>Lütfen resim dosyası yükleyiniz. (jpg, jpeg, gif, png)</li>
					<li>Yüklemeye çalıştığınız resim dosyası 2 MB küçük olmalı</li>
				</ul>
				');
			}		
		}
		?>
        
        <div style="height:194px;" class="text-center">
        <?php if($user['avatar'] == ''): ?>
        	<span class="img-thumbnail"><span class="glyphicon glyphicon-user" style="font-size:150px;"></span></span>
        <?php else: ?>
        	<a href="javascript:;" class="img-thumbnail"><img src="<?php echo base_url($user['avatar']); ?>" width="150" height="100" class="img-responsive" /></a>
        <?php endif; ?>
        </div>
        <?php if($user['id'] == get_the_current_user('id')): ?>
            <label for="avatar"><?php lang('Upload a new photo'); ?></label>
            <input type="file" name="avatar" id="avatar" value="" style="background-color:#fc0; padding:3px; width:100%;" />
            <div style="height:9px;"></div>
            <button class="btn btn-success btn-lg pull-right"><?php lang('Upload a photo'); ?> &raquo;</button>
        <?php endif; ?>
        
    </form>
</div> <!-- /.col-md-4 -->
</div> <!-- /.row -->

