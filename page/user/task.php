<?php include_once('../../header.php'); ?>
<?php if(isset($_GET['type'])) { $type = safety_filter($_GET['type']);	} else 	{ alert_box('alert', get_lang('No Message Type')); exit;	} ?>

<?php change_navigation(get_lang('Task Management')); ?>

<div class="button-bar">
  <ul class="button-group">
    <li><a href="<?php url('page'); ?>/user/task.php?type=receiver" class="button secondary"><?php lang('Tasks from'); ?></a></li>
    <li><a href="<?php url('page'); ?>/user/task.php?type=sender" class="button secondary"><?php lang('Tasks are sent'); ?></a></li>
    <li><a href="<?php url('page'); ?>/user/task_new.php" class="button secondary"><?php lang('New Task'); ?></a></li>
  </ul>
</div>

<div class="row">
	<div class="six columns">
    <?php if($type == 'sender') : ?>
    	<h3><?php lang('Tasks are sent'); ?></h3>
    <?php else : ?>
    	<h3><?php lang('Tasks from'); ?></h3>
    <?php endif; ?>
    <table class="dataTable">
    	<thead>
        	<tr>
            	<th width="1"></th>
                <th><?php lang('Date'); ?></th>
                <th><?php if($type == 'sender') { lang('Receiver'); } else { lang('Sender'); } ?></th>
                <th><?php lang('Task Status'); ?></th>
                <th><?php lang('Title'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
		if($type == 'sender') 
		{
			$query_message = mysql_query("SELECT * FROM $database->message WHERE status='publish' AND type='task' 
			AND sender_id='".get_the_current_user('id')."' 
			AND top_id='0'
			ORDER BY last_date DESC");
		}
		else if($type == 'receiver')
		{
			$query_message = mysql_query("SELECT * FROM $database->message WHERE 
			status='publish' AND type='task' 
			AND inbox_id='".get_the_current_user('id')."'
			AND top_id='0'
			OR
			status='publish' AND type='task' 
			AND receiver_id='".get_the_current_user('id')."'
			AND top_id='0'
			ORDER BY last_date DESC");
		}
		else { alert_box('alert', get_lang('Wrong Message Type')); exit;	}
		
		while($list_message = mysql_fetch_assoc($query_message))
		{
			$last_sate = '';
			if($list_message['last_state'] == 'open' and get_the_current_user('id') == $list_message['inbox_id']) { $last_sate = 'style="text-decoration:underline; font-weight:bold;"';	}
			else if($list_message['last_state'] == 'close') { $last_sate = '';	}
			
			if($type == 'sender'){ $username = get_user($list_message['receiver_id'], 'display_name'); }
			else if($type == 'receiver'){ $username = get_user($list_message['sender_id'], 'display_name'); }
			
			echo '
			<tr>
				<td></td>
				<td '.$last_sate.' style="font-size:9px;">'.substr($list_message['start_date'],0,10).'</td>
				<td '.$last_sate.'>'.substr($username, 0, 6).'</td>
				<td '.$last_sate.'>'.$list_message['task_status'].'</td>
				<td '.$last_sate.'><a href="?type='.$type.'&task_id='.$list_message['id'].'&refresh">'.substr($list_message['title'],0,30).'</a></td>
			</tr>
			';	
		}
		?>
        </tbody>
    </table>
    </div> <!-- /.six columns -->
    <div class="six columns">
    <?php
	if(isset($_GET['task_id']))
	{
		$task_id	=	safety_filter($_GET['task_id']);
		
		// Redirect to the top post.
		$query_top_id = mysql_query("SELECT * FROM $database->message WHERE id='$task_id'");
		while($list_top_id = mysql_fetch_assoc($query_top_id))
		{
			$top_id = $list_top_id['top_id'];
		}
		if($top_id > 0) {$task_id = $top_id;}
		
		
		// Task status change
		if(isset($_GET['task_status'])) 
		{
			$task_status = safety_filter($_GET['task_status']);
			if($task_status == 'open'){}else if($task_status == 'close'){} else { exit; }
			
			mysql_query("UPDATE $database->message SET task_status='$task_status' WHERE id='$task_id'");	
			if(mysql_affected_rows() > 0)
			{
				alert_box('success', get_lang('Successful').' ['.get_lang($task_status).']');
				add_task($task_id, get_config('datetime'), '', get_the_current_user('id'), get_the_task('receiver_id'), '', get_lang('Task Status').': '.get_lang($task_status));
			}
		}
		
		
		$query_message = mysql_query("SELECT * FROM $database->message WHERE type='task' AND id='$task_id'");
		while($list_message = mysql_fetch_assoc($query_message))
		{ # while top message
			$message['id']				=	$list_message['id'];
			$message['status']			=	$list_message['status'];
			$message['type']			=	$list_message['type'];
			$message['last_state']		=	$list_message['last_state'];
			$message['start_date']		=	$list_message['start_date'];
			$message['end_date']		=	$list_message['end_date'];
			$message['top_id']			=	$list_message['top_id'];
			$message['start_date']		=	$list_message['start_date'];
			$message['end_date']		=	$list_message['end_date'];
			$message['sender_id']		=	$list_message['sender_id'];
			$message['receiver_id']		=	$list_message['receiver_id'];
			$message['inbox_id']		=	$list_message['inbox_id'];
			$message['title']			=	$list_message['title'];
			$message['description']		=	$list_message['description'];
			$message['task_status']		=	$list_message['task_status'];
			
			if(get_the_current_user('level') == 1) {} else {
			if($message['sender_id'] == get_the_current_user('id') or $message['receiver_id'] == get_the_current_user('id')){}
			else {alert_box('alert', get_lang('Are not authorized to read this task.'));  exit;} }
			
			// Mark Read
			if(get_the_current_user('id') == $message['inbox_id'])
			{
				mysql_query("UPDATE $database->message SET last_state='close' WHERE id='$task_id'");
				mysql_query("UPDATE $database->message SET last_state='close' WHERE top_id='$task_id'");	
			}
			
			// DELETE MESSAGE
			if(isset($_GET['delete_task_id']))
			{
				$delete_task_id = safety_filter($_GET['delete_task_id']);
				
				if(delete_task($delete_task_id))
				{
					add_log(get_config('datetime'), 'Task Delete', get_lang('Task deleted.').' ['.get_the_current_user('user_name').'] ['.$delete_task_id.']');
					alert_box('alert', get_lang('Deleted'));
				}
			}
			?>
            <div class="row">
            	<div class="twelve columns">
                	<?php if($message['task_status'] == 'open') : ?>
                		<a href="?type=<?php echo $type; ?>&task_id=<?php echo $message['id']; ?>&task_status=close" class="button secondary small"><?php lang('Close this task'); ?></a>
                    <?php else : ?>
                    	<a href="?type=<?php echo $type; ?>&task_id=<?php echo $message['id']; ?>&task_status=open" class="button secondary small"><?php lang('Open this task'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <p></p>
            <div class="panel">
                <div class="row">
                    <div class="twelve columns">
                        <h5><?php echo $message['title']; ?></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="four columns size-11">
                        <img src="<?php url('theme'); ?>/images/icon/16/user_male.png" style="border:1px solid #ccc; padding:2px; float:left; margin-right:5px;" /> 
						<?php lang('Sender'); ?>: <br />
						<a href="#" class="has-tip tip-top" title="<?php user($message['sender_id'], 'display_name'); ?>"  data-reveal-id="box_user_<?php echo $message['sender_id']; ?>"><?php echo substr(get_user($message['sender_id'], 'display_name'),0,10); ?></a>
                        <?php box_user($message['sender_id']); ?>
                    </div>
                    <div class="four columns size-11">
                        <img src="<?php url('theme'); ?>/images/icon/16/user_male.png" style="border:1px solid #ccc; padding:2px; float:left; margin-right:5px;" /> 
						<?php lang('Receiver'); ?>: <br />
                        <a href="#" class="has-tip tip-top" title="<?php user($message['receiver_id'], 'display_name'); ?>" data-reveal-id="box_user_<?php echo $message['receiver_id']; ?>"><?php echo substr(get_user($message['receiver_id'], 'display_name'),0,10); ?></a>
                        <?php box_user($message['receiver_id']); ?>
                    </div>
                    <div class="four columns size-11">
                        <?php echo $message['start_date']; ?>
                    </div>
                </div>
                <p></p>
                <div class="row">
                    <div class="six columns size-11">
                        <strong><?php lang('Start Date'); ?></strong>: <span class="td-underline"><?php echo $message['start_date']; ?></span>
                    </div>
                    <div class="six columns size-11">
                        <strong><?php lang('End Date'); ?></strong>: <span class="td-underline"><?php echo $message['end_date']; ?></span>
                    </div>
                </div>
                <p></p>
                <div class="row">
                    <div class="twelve columns">
                        <span class="td-underline"><?php echo $message['start_date']; ?></span>
                    </div>
                </div>
                <div class="row">
                	<div class="twelve columns text-right">
						<?php if($message['sender_id'] == get_the_current_user('id') or get_the_current_user('level') == 1) : ?>
                            <a href="?type=<?php echo $type; ?>&task_id=<?php echo $task_id; ?>&delete_task_id=<?php echo $message['id']; ?>" class="icon-hover has-tip tip-top"  title="<?php lang('Delete'); ?>"><img src="<?php url('theme/images'); ?>/icon/12/delete.png" /></a>
                         <?php endif; ?>
                     </div> <!-- /.row -->
            	</div>
            </div>
		<?php
		} # /while top messagee
		
		
		if(isset($_POST['btn_reply']))
		{
			$description = safety_filter($_POST['description']);
			$datetime	 = safety_filter($_POST['datetime']);
			
			$reply_message_receiver = '';
			if(get_the_current_user('id') == $message['sender_id'])
			{
				$reply_message_receiver = $message['receiver_id'];
			}
			else
			{
				$reply_message_receiver = $message['sender_id'];
			}
			
			if(!get_log($datetime, get_the_current_user('id'), 'Reply Message', true))
			{
				if(add_task($task_id, $datetime, '', get_the_current_user('id'), $reply_message_receiver, $message['title'], $description))
				{
					mysql_query("UPDATE $database->message SET last_state='open', inbox_id='$reply_message_receiver', last_date='$datetime' WHERE id='$task_id'");
					add_log($datetime, 'Reply Task', get_lang('Reply Task').' ['.get_user($reply_message_receiver, 'user_name').']');
					alert_box('success', get_lang('Successful')); 	
				}
			}
			else {}
		}
		
		$reply_message['inbox_id'] = '';
		$query_reply_message = mysql_query("SELECT * FROM $database->message WHERE status='publish' AND top_id='$task_id'");
		while($list_reply_message = mysql_fetch_assoc($query_reply_message))
		{ # while reply messagee
			$reply_message['id']				=	$list_reply_message['id'];
			$reply_message['status']			=	$list_reply_message['status'];
			$reply_message['type']				=	$list_reply_message['type'];
			$reply_message['last_state']		=	$list_reply_message['last_state'];
			$reply_message['top_id']			=	$list_reply_message['top_id'];
			$reply_message['start_date']		=	$list_reply_message['start_date'];
			$reply_message['end_date']			=	$list_reply_message['end_date'];
			$reply_message['sender_id']			=	$list_reply_message['sender_id'];
			$reply_message['receiver_id']		=	$list_reply_message['receiver_id'];
			$reply_message['inbox_id']			=	$list_reply_message['inbox_id'];
			$reply_message['title']				=	$list_reply_message['title'];
			$reply_message['description']		=	$list_reply_message['description'];
		?>
            <div class="panel">
                <div class="row" >
                    <div class="six columns size-11" style="margin-top:-16px;">
                        <span class="td-underline"><?php echo $reply_message['start_date']; ?></span>
                    </div>
                    <div class="six columns size-11" style="margin-top:-16px;">
                        <img src="<?php url('theme'); ?>/images/icon/16/user_male.png" style="border:1px solid #ccc; padding:2px; float:left; margin-right:5px;" /> 
						<?php lang('Sender'); ?>:<br /> 
                        <a href="#" class="has-tip tip-top" title="<?php user($reply_message['sender_id'], 'display_name'); ?>" data-reveal-id="box_user_<?php echo $reply_message['receiver_id']; ?>"><strong><?php echo substr(get_user($reply_message['sender_id'], 'display_name'),0,10); ?></strong></a>
                        <?php box_user($reply_message['sender_id']); ?>
                    </div>
                </div> <!-- /.row -->
                <p></p>
                <div class="row">
                    <div class="twelve columns">
                        <?php echo $reply_message['description']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns text-right">
                    	<?php if($reply_message['sender_id'] == get_the_current_user('id') or get_the_current_user('level') == 1) : ?>
                        <a href="?type=<?php echo $type; ?>&task_id=<?php echo $task_id; ?>&delete_task_id=<?php echo $reply_message['id']; ?>" title="<?php lang('Delete'); ?>" class="icon-hover has-tip tip-top"><img src="<?php url('theme/images'); ?>/icon/12/delete.png" /></a>
                        <?php endif; ?>
                    </div>
                </div> 
                
                
            </div>

		<?php
		} # /while reply messagee
		
		if(get_the_current_user('id') == $reply_message['inbox_id'])
		{
			mysql_query("UPDATE $database->message SET last_state='close' WHERE id='$task_id'");
			mysql_query("UPDATE $database->message SET last_state='close' WHERE top_id='$task_id'");	
		}
	
		?>
        <form name="form_reply" id="form_reply" action="?type=<?php echo $type; ?>&task_id=<?php echo $message['id']; ?>" method="POST">
        	<textarea name="description" id="description"></textarea>
            <input type="hidden" name="datetime" id="datetime" value="<?php config('datetime'); ?>" />
            <input type="submit" name="btn_reply" id="btn_reply" class="button small" value="<?php lang('Reply'); ?>" />
        </form>
        <?php
	}
	else
	{
		?>
        <h3><?php lang('Recent History'); ?></h3>
        <table class="dataTable">
            <thead>
                <tr>
                    <th width="1"></th>
                    <th><?php lang('End Date'); ?></th>
                    <th><?php if($type == 'sender') { lang('Receiver'); } else { lang('Sender'); } ?></th>
                    <th><?php lang('Task Status'); ?></th>
                    <th><?php lang('Title'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
            if($type == 'sender') 
            {
                $query_message = mysql_query("SELECT * FROM $database->message WHERE status='publish' AND type='task' 
                AND sender_id='".get_the_current_user('id')."' 
                AND top_id='0'
				AND task_status='open'
                ORDER BY end_date ASC");
            }
            else if($type == 'receiver')
            {
                $query_message = mysql_query("SELECT * FROM $database->message WHERE 
                status='publish' AND type='task' 
                AND inbox_id='".get_the_current_user('id')."'
                AND top_id='0'
				AND task_status='open'
                OR
                status='publish' AND type='task' 
                AND receiver_id='".get_the_current_user('id')."'
                AND top_id='0'
				AND task_status='open'
                ORDER BY end_date ASC");
            }
            else { alert_box('alert', get_lang('Wrong Message Type')); exit;	}
            
            while($list_message = mysql_fetch_assoc($query_message))
            {
                $last_sate = '';
                if($list_message['last_state'] == 'open' and get_the_current_user('id') == $list_message['inbox_id']) { $last_sate = 'style="text-decoration:underline; font-weight:bold;"';	}
                else if($list_message['last_state'] == 'close') { $last_sate = '';	}
                
                if($type == 'sender'){ $username = get_user($list_message['receiver_id'], 'display_name'); }
                else if($type == 'receiver'){ $username = get_user($list_message['sender_id'], 'display_name'); }
                
                echo '
                <tr>
                    <td></td>
                    <td '.$last_sate.' style="font-size:9px;">'.substr($list_message['end_date'],0,10).'</td>
                    <td '.$last_sate.'>'.substr($username, 0, 6).'</td>
                    <td '.$last_sate.'>'.$list_message['task_status'].'</td>
                    <td '.$last_sate.'><a href="?type='.$type.'&task_id='.$list_message['id'].'&refresh">'.substr($list_message['title'],0,30).'</a></td>
                </tr>
                ';	
            }
            ?>
            </tbody>
        </table>
        </div> <!-- /.row -->
        <?php
	}
	
	if(isset($_GET['refresh'])) 
	{
		echo '<script> window.location = "?type='.$type.'&task_id='.$message['id'].'"; </script>';	
	}
	
	?>
    
    </div> <!-- /.six columns -->
</div> <!-- /.row -->

<?php include_once('../../footer.php'); ?>