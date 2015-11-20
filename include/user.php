<?php
if(@$_SESSION["management_login"] == true)
{
	$x_query_user = mysql_query("SELECT * FROM $database->users WHERE id='".$_SESSION['user_id']."'");
	while($x_list_user = mysql_fetch_assoc($x_query_user))
	{
		$user['id'] 			= $x_list_user['id'];
		$user['status']			= $x_list_user['status'];
		$user['user_name']		= $x_list_user['user_name'];
		$user['password'] 		= $x_list_user['password'];
		$user['level'] 			= $x_list_user['level'];
		$user['display_name'] 	= $x_list_user['display_name'];
		
		if(strlen($user['display_name']) < 1) {	$user['display_name'] = $x_list_user['user_name'];	}
	}
	
	
	
	$user_id = $user['id'];
	
	function get_the_current_user($value)
	{
		global $user;
		return $user[$value];
	}
	
	function the_current_user($value)
	{
		echo get_the_current_user($value);
	}
}
else
{
	echo '<script>window.location = "'.get_url('').'/login.php"; </script>';
	exit;
}
?>
<?php
/* ----------------------------------------------
	ADD USER
---------------------------------------------- */
function add_user($user_name, $password, $password_again, $level)
{
	global $database;
	$continue = true;
	$user_name		=	safety_filter($user_name);
	$password		=	safety_filter($password);
	$password_again	=	safety_filter($password_again);
	$level			=	safety_filter($level);
	
	if(strlen($user_name) < 3) 	{	$continue = false;	alert_box('alert', get_lang('User Name').' 3 '.get_lang('Minimum Characters'));	}
	if(strlen($user_name) > 20) {	$continue = false;	alert_box('alert', get_lang('User Name').' 20 '.get_lang('Maximum Characters'));	}
	if(filter_var($user_name, FILTER_VALIDATE_EMAIL)) {}
	else if(preg_match("/[^A-Za-z1-9]/i",$user_name))  {	$continue = false;	alert_box('alert', get_lang('User name incorrect'));	}
	if(strlen($password) < 3) 	{	$continue = false;	alert_box('alert', get_lang('Password').' 3 '.get_lang('Minimum Characters'));	}
	if(strlen($password) > 20) 	{	$continue = false;	alert_box('alert', get_lang('Password').' 20 '.get_lang('Maximum Characters'));	}			
	if($password != $password_again)	{ $continue = false;	alert_box('alert', get_lang('Passwords do not match'));	}
	if(mysql_num_rows(mysql_query("SELECT * FROM $database->users WHERE user_name='$user_name'")) > 0)
		{	$continue = false;	alert_box('alert', get_lang('User name database found'));	}
	
	$password	=	md5($password);
	
	if($continue == true)
	{
		mysql_query("INSERT INTO $database->users 
		(status, user_name, password, level)
		VALUES
		('publish', '$user_name', '$password', '$level')
		");
		if(mysql_affected_rows() > 0)
		{
			return mysql_insert_id();	
		}
		else
		{
			return false;	
		}
	}
}

/* ----------------------------------------------
	UPDATE USER
---------------------------------------------- */
function update_user($user_id, $status, $user_name, $password, $level, $display_name)
{
	global $database;
	$continue = true;
	$user_name		=	safety_filter($user_name);
	$password		=	safety_filter($password);
	$level			=	safety_filter($level);
	
	if(strlen($user_name) < 3) 		{	$continue = false;	alert_box('alert', get_lang('User Name').' 3 '.get_lang('Minimum Characters'));	}
	if(strlen($user_name) > 20) {	$continue = false;	alert_box('alert', get_lang('User Name').' 20 '.get_lang('Maximum Characters'));	}
	if(filter_var($user_name, FILTER_VALIDATE_EMAIL)) {}
	else if(preg_match("/[^A-Za-z1-9]/i",$user_name))  			{	$continue = false;	alert_box('alert', get_lang('User name incorrect'));}
	if(strlen($password) > 20) 		{	$continue = false;	alert_box('alert', get_lang('Password').' 20 '.get_lang('Maximum Characters'));	}			
	
	if($password == '')
	{
		$password	=	get_user(get_the_user('id'), 'password');
	}
	else
	{
		$password	=	md5($password);
	}
	
	if($continue == true)
	{
		mysql_query("UPDATE $database->users SET
		status='$status',
		user_name='$user_name',
		password='$password',
		level='$level'
		WHERE
		id='$user_id'
		");
		if(mysql_affected_rows() > 0)
		{
			return true;	
		}
		else
		{
			return false;	
		}
	}
}

/* ----------------------------------------------
	DELETE USER
---------------------------------------------- */
function delete_user($user_id)
{
	global $database;
	$user_id	=	safety_filter($user_id);
	
	mysql_query("UPDATE $database->users SET status='delete' WHERE id='$user_id'");	
	if(mysql_affected_rows() > 0)
	{
		return true;
	}
}


/* ----------------------------------------------
	GET THE USER
---------------------------------------------- */
if(isset($_GET['user_id']) or isset($_POST['user_id']))
{
	if(isset($_GET['user_id']))			{ $user_id = safety_filter($_GET['user_id']);	}
	else if(isset($_POST['user_id']))	{ $user_id = safety_filter($_POST['user_id']);	}
	
	$query_users = mysql_query("SELECT * FROM $database->users WHERE id='$user_id'");
	while($list_users = mysql_fetch_assoc($query_users))
	{
		$users['id']				=	$list_users['id'];
		$users['status']			=	$list_users['status'];
		$users['user_name']			=	$list_users['user_name'];
		$users['password']			=	$list_users['password'];
		$users['level']				=	$list_users['level'];
		$users['display_name'] 		= 	$list_users['display_name'];
		
		if(strlen($users['display_name']) < 1) {	$users['display_name'] = $list_users['user_name'];	}
	}
	
	function get_the_user($value)
	{
		global $users;
		return	$users[$value];
	}
	
	function the_user($value)
	{
		echo get_the_user($value);
	}
}


/* ----------------------------------------------
	GET USER
---------------------------------------------- */
function get_user($user_id, $value)
{	
	global $database;
	
	$query_users = mysql_query("SELECT * FROM $database->users WHERE id='$user_id'");
	while($list_users = mysql_fetch_assoc($query_users))
	{
		$users['id']				=	$list_users['id'];
		$users['status']			=	$list_users['status'];
		$users['user_name']			=	$list_users['user_name'];
		$users['password']			=	$list_users['password'];
		$users['level']				=	$list_users['level'];
		$users['display_name'] 		= 	$list_users['display_name'];
		
		if(strlen($users['display_name']) < 1) {	$users['display_name'] = $list_users['user_name'];	}
	}
	
	
	return $users[$value];
}

function user($user_id, $value)
{
	echo get_user($user_id, $value);
}



/* ----------------------------------------------
	BOX USER
---------------------------------------------- */
function box_user($user_id)
{
	global $database;
	$query_users = mysql_query("SELECT * FROM $database->users WHERE id='$user_id'");
	while($list_users = mysql_fetch_assoc($query_users))
	{
		$users['id']				=	$list_users['id'];
		$users['status']			=	$list_users['status'];
		$users['user_name']			=	$list_users['user_name'];
		$users['password']			=	$list_users['password'];
		$users['level']				=	$list_users['level'];
		$users['display_name'] 		= 	$list_users['display_name'];
		
		if(strlen($users['display_name']) < 1) {	$users['display_name'] = $list_users['user_name'];	}
	}
	
	echo '
		<div id="box_user_'.$users['id'].'" class="reveal-modal">
			
			<div class="row">
				<div class="two columns">
					<img src="'.get_url('theme').'/images/icon/16/user_male.png" style="border:1px solid #ccc; padding:2px; float:left; margin-right:5px;" />
				</div>
				<div class="ten columns">
					<h4>'.$users['user_name'].' / '.$users['display_name'].'</h4>
				</div>
			</div>
		  	<hr />
		  	<div class="button-bar">
				<ul class="button-group">
					<li><a href="'.get_url('page').'/user/message_new.php?user_id='.$users['id'].'" class="button secondary">'.get_lang('New Message').'</a></li>
					<li><a href="'.get_url('page').'/user/task_new.php?user_id='.$users['id'].'" class="button secondary">'.get_lang('New Task').'</a></li>
					
				</ul>
			</div>
			<a class="close-reveal-modal">&#215;</a>
		</div>
	';	
}
?>


<?php
/* ----------------------------------------------
	ADD MESSAGE
---------------------------------------------- */
function add_message($top_id, $start_date, $sender_id, $receiver_id, $title, $description)
{
	global $database;
	$top_id			=	safety_filter($top_id);
	$start_date		=	safety_filter($start_date);
	$sender_id		=	safety_filter($sender_id);
	$receiver_id	=	safety_filter($receiver_id);
	$title			=	safety_filter($title);
	$description	=	safety_filter($description);
	
	mysql_query("INSERT INTO $database->message
	(status, type, last_state, top_id, start_date, end_date, last_date, sender_id, receiver_id, inbox_id, title, description)
	VALUES
	('publish', 'message', 'open', '$top_id', '$start_date', '', '$start_date', '$sender_id', '$receiver_id', '$receiver_id', '$title', '$description')");
	if(mysql_affected_rows() > 0)
	{
		return mysql_insert_id();
	}
	else
	{
		echo mysql_error();
		return false;
	}

}



/* ----------------------------------------------
	DELETE MESSAGE
---------------------------------------------- */
function delete_message($delete_id)
{
	global $database;
	$delete_id = safety_filter($delete_id);
	$update = mysql_query("UPDATE $database->message SET status='delete' WHERE id='$delete_id'");
	if(mysql_affected_rows() > 0)
	{
		return true;
	}
	else
	{
		if($update){ return true;	}
		else { return false;	}	
	}
}


/* ----------------------------------------------
	GET TOTAL MESSAGE
---------------------------------------------- */
function get_total_message($last_state, $receiver_id)
{
	$last_state 	= safety_filter($last_state);
	$receiver_id 	= safety_filter($receiver_id);
	
	global $database;
	$total = mysql_num_rows(mysql_query("SELECT * FROM $database->message WHERE 
	status='publish' AND type='message' AND last_state='$last_state' AND inbox_id='$receiver_id' AND top_id='0'
	OR
	status='publish' AND type='message' AND last_state='$last_state' AND receiver_id='$receiver_id' AND top_id='0'"));	
	return $total;
}
function total_message($last_state, $receiver_id)
{
	echo get_total_message($last_state, $receiver_id);
}



?>



<?php
/* ----------------------------------------------
	ADD TASK
---------------------------------------------- */
function add_task($top_id, $start_date, $end_date, $sender_id, $receiver_id, $title, $description)
{
	global $database;
	$top_id			=	safety_filter($top_id);
	$start_date		=	safety_filter($start_date);
	$end_date		=	safety_filter($end_date);
	$sender_id		=	safety_filter($sender_id);
	$receiver_id	=	safety_filter($receiver_id);
	$title			=	safety_filter($title);
	$description	=	safety_filter($description);
	
	mysql_query("INSERT INTO $database->message
	(status, type, last_state, top_id, start_date, end_date, last_date, sender_id, receiver_id, inbox_id, title, description, task_status)
	VALUES
	('publish', 'task', 'open', '$top_id', '$start_date', '$end_date', '$start_date', '$sender_id', '$receiver_id', '$receiver_id', '$title', '$description', 'open')");
	if(mysql_affected_rows() > 0)
	{
		return mysql_insert_id();
	}
	else
	{
		echo mysql_error();
		return false;
	}

}


/* ----------------------------------------------
	DELETE TASK
---------------------------------------------- */
function delete_task($delete_id)
{
	global $database;
	$delete_id = safety_filter($delete_id);
	$update = mysql_query("UPDATE $database->message SET status='delete' WHERE id='$delete_id'");
	if(mysql_affected_rows() > 0)
	{
		return true;
	}
	else
	{
		return false;	
	}
}


/* ----------------------------------------------
	GET THE TASK
---------------------------------------------- */
if(isset($_GET['task_id']))
{
	$task_id = safety_filter($_GET['task_id']);
	$query_task = mysql_query("SELECT * FROM $database->message WHERE type='task' AND id='$task_id'");
	while($list_task = mysql_fetch_assoc($query_task))
	{ # while top message
		$task['id']				=	$list_task['id'];
		$task['status']			=	$list_task['status'];
		$task['type']			=	$list_task['type'];
		$task['last_state']		=	$list_task['last_state'];
		$task['start_date']		=	$list_task['start_date'];
		$task['end_date']		=	$list_task['end_date'];
		$task['top_id']			=	$list_task['top_id'];
		$task['start_date']		=	$list_task['start_date'];
		$task['end_date']		=	$list_task['end_date'];
		$task['sender_id']		=	$list_task['sender_id'];
		$task['receiver_id']	=	$list_task['receiver_id'];
		$task['inbox_id']		=	$list_task['inbox_id'];
		$task['title']			=	$list_task['title'];
		$task['description']	=	$list_task['description'];
		$task['task_status']	=	$list_task['task_status'];
	}
	
	function get_the_task($value)
	{
		global $task;	
		return $task[$value];
	}
	
	function the_task($value)
	{
		echo get_the_task($value);
	}
}



/* ----------------------------------------------
	GET TOTAL TASK
---------------------------------------------- */
function get_total_task($last_state, $receiver_id)
{
	$last_state 	= safety_filter($last_state);
	$receiver_id 	= safety_filter($receiver_id);
	
	global $database;
	$total = mysql_num_rows(mysql_query("SELECT * FROM $database->message WHERE status='publish' AND type='task' AND task_status='$last_state' AND inbox_id='$receiver_id' AND top_id='0'"));	
	return $total;
}
function total_task($last_state, $receiver_id)
{
	echo get_total_task($last_state, $receiver_id);
}
?>