<?php session_start(); ?>
<?php ob_start(); ?>
<?php
include_once('configuration.php');
include_once('include/connect.php');
include_once('include/global.php');
include_once('include/language.php');
?>
<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title><?php config('name'); ?></title>

  <!-- Included CSS Files -->
  <link rel="stylesheet" href="<?php url('theme'); ?>/stylesheets/foundation.css">
  <link rel="stylesheet" href="<?php url('theme'); ?>/stylesheets/app.css">

  <!-- Included JS Files (Compressed) -->
  <script src="<?php url('theme'); ?>/javascripts/modernizr.foundation.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/foundation.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.navigation.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.reveal.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.orbit.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.buttons.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.tabs.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.forms.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.tooltips.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.accordion.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.placeholder.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.alerts.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.validate.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php url('theme'); ?>/javascripts/app.js"></script>

  <!-- IE Fix for HTML5 Tags -->
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

</head>
<body>

<p></p>

<div class="row">
	<div class="five columns end">
    <?php
	if(isset($_POST['btn_submit']))
	{
		 $user_name = safety_filter($_POST['user_name']);
		 $password 	= md5(safety_filter($_POST['password']));
		 
		 $query_user = mysql_query("SELECT * FROM $database->users WHERE user_name='$user_name' AND password='$password' AND status='publish'");
		 if(mysql_num_rows($query_user) > 0)
		 {
			 while($list_user = mysql_fetch_assoc($query_user))
			{
				$user_id = $list_user['id'];
			}
			$management_login = true;
			$user_id = $user_id;
			
			$_SESSION["management_login"] = true;
			$_SESSION['user_id'] = $user_id;
			
			echo '<script> window.location = "index.php"; </script>';
		 }
		 else
		 {
			alert_box('alert', get_lang('Username or password is incorrect.')); 
		 }
	}
	?>
    </div>
</div> <!-- /.row -->


<div class="row">
	<div class="five columns end">
    	<form name="form_login" id="form_login" action="" method="POST">
        	<fieldset>
          	<legend><?php lang('Login Form'); ?></legend>
    
              <label for="user_name"><?php lang('Login Name'); ?>: admin</label>
              <input type="text" name="user_name" id="user_name" class="required" minlength="3" maxlength="20" value="admin" />
    
              <label for="password"><?php lang('Password'); ?>: 123456</label>
              <input type="password" name="password" id="password" class="required"  minlength="3" maxlength="20" value="123456" />          
    			
              <input type="submit" name="btn_submit" id="btn_submit" class="button" value="<?php lang('Login'); ?>" />
              
              <p></p>  
              
        </fieldset>
      </form>
    </div> <!-- /.twelve columns -->
</div> <!-- /.row -->