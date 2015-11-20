<legend class="ff-1"><span class="glyphicon glyphicon-plus-sign mr9"></span><?php lang('Bulk Message'); ?></legend>
<?php $users = get_user_array(); ?>


<div class="row">
	<div class="col-md-8">
    
    	<?php
		if(isset($_POST['new_message']) and is_log())
		{
			$continue = true;
			$this->form_validation->set_rules('title', get_lang('Title'), 'required|min_length[3]|max_length[50]');
			$this->form_validation->set_rules('content', get_lang('Message'), 'required|min_length[3]|max_length[5000]');
		
			if($this->form_validation->run() == FALSE)
			{
				alertbox('alert-danger', '', validation_errors());
			}
			else
			{
				$this->db->where_not_in('id', get_the_current_user('id'));
				if($_POST['role'] != 0){$this->db->where('role', $_POST['role']);}
				$this->db->where('status', '1');
				$get_users = $this->db->get('users')->result_array();
				
				
				foreach($get_users as $get_user)
				{
					$data['receiver_id'] = $get_user['id'];
					$data['title'] = $this->input->post('title');
					$data['content'] = $this->input->post('content');
					$message_id = add_message($data);
					if($message_id > 0)
					{
						add_log(array('date'=>$_POST['log_time'], 'type'=>'message', 'title'=>get_lang('New Message'), 'description'=>get_lang('Send a new message').' ['.$get_user['name'].' '.$get_user['surname'].']'));
						
					}
				}
				
				alertbox('alert-success', get_lang('Message has been sent.'));
			}
		}
		?>
        
        <?php
		$user_data['id'] = '';
		$user_data['display_name'] = '';
		if(isset($_GET['user_id']))
		{
			$user_data['id'] = $_GET['user_id'];
			$user_data = get_user($user_data);
		}
		?>
    
        <form name="form_new_product" id="form_new_product" action="" method="POST" class="validation">
            <div class="form-group openModal-user_list">
                <label for="role" class="control-label ff-1 fs-16"><?php lang('Receiver Group'); ?></label>
                <select name="role" id="role" class="form-control input-lg">
                	<option value="0"><?php lang('Everyone'); ?></option>
                 	<option value="5"><?php lang('Staff'); ?></option>
                 	<option value="4"><?php lang('Authorized Personnel'); ?></option>
                	<option value="3"><?php lang('Chief'); ?></option>
                 	<option value="2"><?php lang('Admin'); ?></option>
                 	<option value="1"><?php lang('Super Admin'); ?></option>
                </select>
            </div> <!-- /.form-group -->
            <div class="form-group">
                <label for="title" class="control-label ff-1 fs-16"><?php lang('Title'); ?></label>
                <div class="input-prepend input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-text-width"></span></span>
                    <input type="text" id="title" name="title" class="form-control input-lg ff-1 required" placeholder="<?php lang('Title'); ?>" minlength="3" maxlength="50" value="">
                </div>
            </div> <!-- /.form-group -->
            <div class="form-group">
                <label for="content" class="control-label ff-1 fs-16"><?php lang('Message'); ?></label>
                <textarea style="width:100%; height:160px;" class="form-control required" name="content" id="content" minlength="3"></textarea>
            </div> <!-- /.form-group -->
            
             <div class="text-right">
             	<input type="hidden" name="log_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
                <input type="hidden" name="new_message" />
                <button class="btn btn-default btn-lg"><?php lang('Send'); ?> &raquo;</button>
            </div> <!-- /.text-right -->
        </form>
       
    </div> <!-- /.col-md-8 -->
    <div class="col-md-4">
    	<div class="box_title turq"><span class="glyphicon glyphicon-envelope mr5"></span><?php lang('Inbox'); ?></div>
   		<table class="table table-hover table-bordered">
        	<thead>
            	<tr>
                    <th><?php lang('Sender'); ?></th>
                    <th><?php lang('Title'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
			$this->db->where('status', 1);
			$this->db->where_in('type', array('message','reply_message'));
			$this->db->where('inbox_view', '1');
			$this->db->where('receiver_id', get_the_current_user('id'));		
			$this->db->order_by('recent_activity', 'DESC');
			$this->db->limit(10);
			$query = $this->db->get('user_mess')->result_array();
			
			foreach($query as $q):	
			?>
               <tr class="<?php if(strstr($q['read'], '['.get_the_current_user('id').']')){ echo 'active strong';} ?>">
                    <td><?php echo $users[$q['sender_id']]['surname']; ?></td>
                    <td><a href="<?php echo site_url('user/inbox/'.$q['id']); ?>"><?php echo $q['title']; ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div> <!-- /.col-md-4 -->
</div> <!-- /.row -->


<script>
$(document).ready(function(e) {
    $('.openModal-user_list').click(function() {
		$('#modal-user_list').click();
	});
});
</script>