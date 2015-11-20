<legend class="ff-1"><span class="glyphicon glyphicon-log-in mr9"></span><?php lang('Inbox'); ?></legend>
<?php $users = get_user_array(); ?>


<?php if(@$message_id != ''): ?>

<?php
// reload information
$this->db->where('id', $message_id);
$message = $this->db->get('user_mess')->row_array();

if(isset($_GET['delete']) and get_the_current_user('role') <= 2)
{
	$this->db->where('id', $_GET['delete']);
	$this->db->update('user_mess', array('status'=>'0'));
	
	$this->db->where('top_id', $_GET['delete']);
	$this->db->update('user_mess', array('status'=>'0'));
	
	$log['type'] = 'message';
	$log['other_id'] = 'message:'.$message['id'];
	$log['title'] = get_lang('Message');
	$log['description'] = get_lang('Status Changed').' ['.$_GET['delete'].']';
	add_log($log);	
}

// reload information
$this->db->where('id', $message_id);
$message = $this->db->get('user_mess')->row_array();

if($message['top_id'] > 0){redirect(site_url('user/inbox/'.$message['top_id']));}


if($message['status'] == '0')
{
	alertbox('alert-warning', get_lang('This message has been deleted.'), '', false);
}

// read message
if($message['read']=='['.get_the_current_user('id').']')
{
	if($message['receiver_id'] == get_the_current_user('id'))
	{
		$this->db->where('id', $message['id']);
		$this->db->update('user_mess', array('read'=>'0', 'read_date'=>date("Y-m-d H:i:s")));
	}
}
?>

<?php $show_message = true; ?>
<?php if($message['sender_id'] == get_the_current_user('id')): ?>
<?php elseif($message['receiver_id'] == get_the_current_user('id')): ?>
<?php elseif(get_the_current_user('role') <= 2): ?>
<?php else: ?>
	<?php alertbox('alert-danger', get_lang('Error!')); ?>
    <?php $show_message = false; ?>
<?php endif; ?>

<?php if($show_message): ?>
<div class="row">
	<div class="col-md-8">
        
        <?php
		if(isset($_POST['reply']) and is_log())
		{
			$continue = true;
			$this->form_validation->set_rules('content', get_lang('Message'), 'required|min_length[3]|max_length[1000]');
		
			if($this->form_validation->run() == FALSE)
			{
				alertbox('alert-danger', '', validation_errors());
			}
			else
			{
				if($message['receiver_id'] == get_the_current_user('id'))
				{
					$data['receiver_id'] 	= $message['sender_id'];
					$data['sender_id'] 		= $message['receiver_id'];
					$data['read']			= '['.$message['sender_id'].']';
				}
				elseif($message['sender_id'] == get_the_current_user('id'))
				{
					$data['receiver_id'] 	= $message['receiver_id'];
					$data['sender_id'] 		= $message['sender_id'];
					$data['read']			= '['.$message['receiver_id'].']';
				}
				
				$data['title'] = $message['title'];
				$data['top_id'] = $message['id'];
				$data['type'] = 'reply_message';
				$data['content'] = $this->input->post('content');
				$message_id = add_message($data);
				if($message_id > 0)
				{
					add_log(array('date'=>$_POST['log_time'], 'type'=>'message', 'title'=>get_lang('New Message'), 'description'=>get_lang('Send a new message').' ['.$message['id'].']'));
					alertbox('alert-success', get_lang('Message has been sent.'));
					
					
					// If sending the same message to the recipient
					if($message['receiver_id'] == get_the_current_user('id'))
					{
						$this->db->where('id', $message['id']);
						$this->db->update('user_mess', array('inbox_view'=>'1', 'recent_activity'=>date("Y-m-d H:i:s")));
					}
				}
				else
				{
					alertbox('alert-danger', get_lang('Error!'));
				}
			}
		}
		?>
        
        <div class="row">
        	<div class="col-md-2"></div>
            <div class="col-md-10"><h3 class="ff-2 text-warning"><?php echo $message['title']; ?></h3></div>
        </div>
        
        <div class="row">
        	<div class="col-md-2">
            	<a href="<?php echo site_url('user/profile/'.$message['sender_id']); ?>" class="img-thumbnail">
                	<?php if($users[$message['sender_id']]['avatar'] == ''): ?>
                		<span class="glyphicon glyphicon-user" style="font-size:72px;"></span>
                    <?php else: ?>
                    	<img src="<?php echo base_url($users[$message['sender_id']]['avatar']); ?>" width="150" height="100" class="img-responsive" />
                    <?php endif; ?> 
                    <small><span class="glyphicon glyphicon-user mr2"></span> <?php echo $users[$message['sender_id']]['surname']; ?></small>
                </a>
            </div> <!-- /.col-md-4 -->
            <div class="col-md-10">
            	<blockquote>
                    <div class="messageContent">
                    	<a href="<?php echo site_url('user/profile/'.$message['sender_id']); ?>"><h4><?php echo $users[$message['sender_id']]['name']; ?> <?php echo $users[$message['sender_id']]['surname']; ?></h4></a>
                    	<?php echo $message['content']; ?>
                    </div>
                   
                    <small><?php lang('Written on'); ?>: <span class="text-success"><?php echo substr($message['date'],0,16); ?></span>, <?php lang('Read on'); ?>: <span class="text-warning"><?php echo substr($message['read_date'],0,16); ?></span> 
                    <?php if(get_the_current_user('role') <= 2): ?><span class="pull-right"><a href="<?php echo site_url('user/inbox/'.$message['id'].'/?delete='.$message['id'].''); ?>" class="text-danger strong"><span class="glyphicon glyphicon-remove-circle"></span> <?php lang('Delete'); ?></a>  </span><?php endif; ?>
                    </small>
            	</blockquote>
                <div class="hr"></div>
            </div> <!-- /.col-md-8 -->
        </div> <!-- /.row -->
        
        <div class="h20"></div>
        
        <?php
		$this->db->where('status', '1');
		$this->db->where('top_id', $message['id']);
		$this->db->order_by('date', 'ASC');
		$replys = $this->db->get('user_mess')->result_array();
		?>
        <?php foreach($replys as $reply): ?>
        <?php
		// read message
		if($reply['read']=='['.get_the_current_user('id').']')
		{
			if($reply['receiver_id'] == get_the_current_user('id'))
			{
				$this->db->where('id', $reply['id']);
				$this->db->update('user_mess', array('read'=>'0', 'read_date'=>date("Y-m-d H:i:s")));
			}
		}
		?>
        <div class="row">

        	<div class="col-md-2">
            	<a href="<?php echo site_url('user/profile/'.$reply['sender_id']); ?>" class="img-thumbnail">
                	<?php if($users[$reply['sender_id']]['avatar'] == ''): ?>
                		<span class="glyphicon glyphicon-user" style="font-size:72px;"></span>
                    <?php else: ?>
                    	<img src="<?php echo base_url($users[$reply['sender_id']]['avatar']); ?>" width="150" height="100" class="img-responsive" />
                    <?php endif; ?>
                    <small><span class="glyphicon glyphicon-user mr2"></span> <?php echo $users[$reply['sender_id']]['surname']; ?></small>
                </a>
            </div> <!-- /.col-md-4 -->
            <div class="col-md-10">
            	<blockquote>
                    <div class="messageContent">
                    	<a href="<?php echo site_url('user/profile/'.$reply['sender_id']); ?>"><h4><?php echo $users[$reply['sender_id']]['name']; ?> <?php echo $users[$reply['sender_id']]['surname']; ?></h4></a>
                    	<?php echo $reply['content']; ?>
                    </div>
                    <small><?php lang('Written on'); ?>: <span class="text-success"><?php echo substr($reply['date'],0,16); ?></span>, <?php lang('Read on'); ?>: <span class="text-warning"><?php echo substr($reply['read_date'],0,16); ?></span> </small>
            	</blockquote>
                <div class="hr"></div>
            </div> <!-- /.col-md-8 -->
        </div> <!-- /.row -->
        <div class="h20"></div>
        <?php endforeach; ?>
        
        
        <div class="h20"></div>
        <div class="h20"></div>
        
        <form name="form_reply" id="form_reply" action="" method="POST" class="validation">
        	<div class="form-group">
                <label for="content" class="control-label ff-1 fs-16"><?php lang('Reply'); ?></label>
                <textarea style="width:100%; height:100px;" class="form-control required" name="content" id="content" minlength="3"></textarea>
            </div> <!-- /.form-group -->
            
             <div class="text-right">
             	<input type="hidden" name="log_time" value="<?php echo date("Y-m-d H:i:s"); ?>" />
                <input type="hidden" name="reply" />
                <button class="btn btn-default btn-lg"><?php lang('Send'); ?> &raquo;</button>
            </div> <!-- /.text-right -->
        </form>
        
        
        
    </div> <!-- /.col-md-8 -->
    <div class="col-md-4">
    	<div class="box_title red"><span class="glyphicon glyphicon-envelope mr9"></span><?php lang('Inbox'); ?></div>
    	<table class="table table-hover table-bordered table-condensed">
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
                    <td><a href="<?php echo site_url('user/inbox/'.$q['id']); ?>"><?php echo mb_substr($q['title'],0,30,'utf-8'); ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div> <!-- /.col-md-4 -->
</div> <!-- /.row -->
<?php endif; ?>

<?php else: ?>
<div class="row">
	<div class="col-md-12">
    	<table class="table table-hover table-bordered table-condensed table-striped dataTable">
        	<thead>
            	<tr>
                	<th></th>
                    <th><?php lang('Date'); ?></th>
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
			$query = $this->db->get('user_mess')->result_array();
			
			foreach($query as $q):	
			?>
                <tr class="<?php if(strstr($q['read'], '['.get_the_current_user('id').']')){ echo 'active strong';} ?>">
                    <td></td>
                    <td><?php echo substr($q['date'],0,16); ?></td>
                    <td><?php echo $users[$q['sender_id']]['name'].' '.$users[$q['sender_id']]['surname']; ?></a></td>
                    <td><a href="<?php echo site_url('user/inbox/'.$q['id']); ?>"><?php echo $q['title']; ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    
    </div> <!-- /.col-md-12 -->
</div> <!-- /.row -->
<?php endif; ?>