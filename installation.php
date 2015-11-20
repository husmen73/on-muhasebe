<?php session_start(); ?>
<?php ob_start(); ?>
<?php
include_once('configuration.php');
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
<?php  if(is_writable('configuration.php')) : ?>
<div class="row">
	<div class="five columns end">
    <?php
	if(isset($_POST['btn_installation']))
	{
		$continue = true;
		$db_host		=	trim(strip_tags($_POST['db_host']));
		$db_user_name	=	trim(strip_tags($_POST['db_user_name']));
		$db_password	=	trim(strip_tags($_POST['db_password']));
		$db_name		=	trim(strip_tags($_POST['db_name']));
		$prefix			=	trim(strip_tags($_POST['prefix']));
		$user_name		=	trim(strip_tags($_POST['user_name']));
		$password		=	trim(strip_tags($_POST['password']));
		$url 			= 	'http://'.$_SERVER['SERVER_NAME'].str_replace('/installation.php', '', $_SERVER['SCRIPT_NAME']);
		 
		$db_connect = @mysql_connect($db_host,$db_user_name,$db_password);
			if(!$db_connect){ alert_box('alert', get_lang('No connection to the server'));  $continue = false; }
		$db_select = mysql_select_db($db_name);
			if(!$db_select){ alert_box('alert', get_lang('The database was not found')); $continue = false; }
		
		$user_name		=	safety_filter($user_name);
		$password		=	safety_filter($password);
		
		if(strlen($user_name) < 3) 	{	$continue = false;	alert_box('alert', get_lang('User Name').' 3 '.get_lang('Minimum Characters'));	}
		if(strlen($user_name) > 20) {	$continue = false;	alert_box('alert', get_lang('User Name').' 20 '.get_lang('Maximum Characters'));}
		if(filter_var($user_name, FILTER_VALIDATE_EMAIL)) {}
		else if(preg_match("/[^A-Za-z1-9]/i",$user_name)) {	$continue = false;	alert_box('alert', get_lang('User name incorrect'));		}
		if(strlen($password) < 3) 	{	$continue = false;	alert_box('alert', get_lang('Password').' 3 '.get_lang('Minimum Characters'));	}
		if(strlen($password) > 20) 	{	$continue = false;	alert_box('alert', get_lang('Password').' 20 '.get_lang('Maximum Characters'));	}			
			$password = md5($password);
		
		if($continue == true)
		{
			# configuration.php			
			$cnc_config_file = fopen( "configuration.php" , "w" ) or die ("Unable to open file!");
			$cnc_text = '
			<?php
			/* ----------------------------------------------
			MYSQL DATABASE SETTINGS
			---------------------------------------------- */
			$db_host = \''.$db_host.'\'; 
			$db_name = \''.$db_name.'\';
			$db_user_name = \''.$db_user_name.'\';
			$db_password = \''.$db_password.'\';
			$prefix = \''.$prefix.'\';
			$url = \''.$url.'\';
			?>
			';
			fwrite ( $cnc_config_file , $cnc_text ) ;
			fclose ( $cnc_config_file );  
				
			mysql_query("CREATE TABLE ".$prefix."users
			(
				id INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(id),
				status VARCHAR(20),
				user_name VARCHAR(50),
				password VARCHAR(100),
				level CHAR(1),
				display_name VARCHAR(50)
			)
			CHARACTER SET utf8 COLLATE utf8_general_ci
			");
			echo mysql_error();
			mysql_query("INSERT INTO ".$prefix."users
			(status, user_name, password, level, display_name)
			VALUES
			('publish', '$user_name', '$password', '1', '')
			");
				
				
					
			mysql_query("CREATE TABLE ".$prefix."meta
			(
				id INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(id),
				ref_id INT,
				title VARCHAR(255),
				meta_key VARCHAR(255),
				meta_value VARCHAR(255)
			)
			CHARACTER SET utf8 COLLATE utf8_general_ci
			");
			echo mysql_error();
			mysql_query("INSERT INTO ".$prefix."meta
			(ref_id, title, meta_key, meta_value)
			VALUES
			('', 'settings', 'language', 'english.php')");
			
			
			
			mysql_query("CREATE TABLE ".$prefix."message
			(
				id INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(id),
				status VARCHAR(20),
				type VARCHAR(20),
				last_state VARCHAR(20),
				top_id INT,
				start_date DATETIME,
				end_date DATETIME,
				last_date DATETIME,
				sender_id INT,
				receiver_id INT,
				inbox_id INT,
				title VARCHAR(255),
				description TINYTEXT,
				task_status VARCHAR(20)
			)
			CHARACTER SET utf8 COLLATE utf8_general_ci
			");
			echo mysql_error();
			
			
			
			mysql_query("CREATE TABLE ".$prefix."logs
			(
				id INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(id),
				date DATETIME,
				user_id INT,
				product_id INT,
				current_id INT,
				fiche_id INT,
				title VARCHAR(255),
				description VARCHAR(255)
			)
			CHARACTER SET utf8 COLLATE utf8_general_ci
			");
			echo mysql_error();
			
			
			/* ----- PRODUCT ----- */
			mysql_query("CREATE TABLE ".$prefix."products
			(
				id INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(id),
				status VARCHAR(20) DEFAULT 'publish',
				code VARCHAR(50),
				name VARCHAR(50),
				cost_price DECIMAL(15,4),
				sale_price DECIMAL(15,4),
				quantity DECIMAL(15,5)
			)
			CHARACTER SET utf8 COLLATE utf8_general_ci
			");
			echo mysql_error();
			
			
			/* ----- CURRENT ----- */
			mysql_query("CREATE TABLE ".$prefix."currents
			(
				id INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(id),
				status VARCHAR(20) DEFAULT 'publish',
				code VARCHAR(50),
				name VARCHAR(50),
				balance DECIMAL(15,4)
			)
			CHARACTER SET utf8 COLLATE utf8_general_ci
			");
			echo mysql_error();
			
			
			/* ----- FICHE ----- */
			mysql_query("CREATE TABLE ".$prefix."fiche
			(
				id INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(id),
				status VARCHAR(20) DEFAULT 'publish',
				type VARCHAR(20),
				date DATETIME,
				current_id INT,
				total DECIMAL(15,4),
				tax_rate INT(3),
				tax DECIMAL(15,4),
				grand_total DECIMAL(15,4),
				fiche_items INT
			)
			CHARACTER SET utf8 COLLATE utf8_general_ci
			");
			echo mysql_error();
			
			
			/* ----- FICHE ITEMS ----- */
			mysql_query("CREATE TABLE ".$prefix."fiche_items
			(
				id INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(id),
				status VARCHAR(20) DEFAULT 'publish',
				type VARCHAR(20),
				date DATETIME,
				fiche_id INT,
				current_id INT,
				product_id INT,
				quantity DECIMAL(15,4),
				cost_price DECIMAL(15,4),
				price DECIMAL(15,4),
				total DECIMAL(15,4)
			)
			CHARACTER SET utf8 COLLATE utf8_general_ci
			");
			echo mysql_error();
			
			
			echo '<script> window.location = "?success"; </script>';
		}
	}
	?>
    </div>
</div> <!-- /.row -->

<?php if(isset($_GET['success'])) : ?>

<div class="row">
	<div class="twelve columns">
    	<div class="alert-box success"><h3><?php lang('Installation was successful'); ?></h3></div>
        <a href="login.php" class="button"><?php lang('Login'); ?> &raquo;</a>
    </div>
</div>

<?php else : ?>

<div class="row">
	<div class="twelve columns">
    	<h3><?php lang('Welcome to the installation'); ?></h3>
    </div>
</div>

<form name="form_installation" id="form_installation" action="" method="POST">
	<div class="row">
		<div class="six columns">
        	<fieldset>
          	<legend><?php lang('Installation'); ?></legend>
    
            <label for="db_host"><?php lang('Host'); ?></label>
            <input type="text" name="db_host" id="db_host" class="required" minlength="3" maxlength="20" placeholder="localhost" value="localhost" />
            
            <label for="db_user_name"><?php lang('Database User Name'); ?></label>
            <input type="text" name="db_user_name" id="db_user_name" class="required" />  
            
            <label for="db_password"><?php lang('Database Password'); ?></label>
            <input type="text" name="db_password" id="db_password" />  
            
            <label for="db_name"><?php lang('Database Name'); ?></label>
            <input type="text" name="db_name" id="db_name" class="required" />
            
            <label for="prefix"><?php lang('Prefix'); ?></label>
            <input type="text" name="prefix" id="prefix" placeholder="cnc_" />          
    			   
        </fieldset>
        </div> <!-- /.six columns -->
        <div class="six columns">
        <fieldset>
			<legend><?php lang('Admin'); ?></legend>
    
            <label for="user_name"><?php lang('User Name'); ?></label>
            <input type="text" name="user_name" id="user_name" class="required" maxlength="20" minlength="3" />  
            
            <label for="password"><?php lang('Password'); ?></label>
            <input type="text" name="password" id="password" class="required" maxlength="20" minlength="3" />  
                 
        </fieldset>
        </div> <!-- /.six columns -->
	</div> <!-- /.row -->
    <div class="row">
    	<div class="twelve columns">
        	<input type="submit" name="btn_installation" id="btn_installation" class="button" value="<?php lang('Install'); ?> &raquo;" />
        </div>
    </div>
</form>

<?php endif ; ?>

<?php else : ?>

<div class="row">
	<div class="twelve columns">
    	<?php alert_box('alert', get_lang('configuration.php file not writable. Please CHMOD value to 777.')); ?>
    </div>
</div>

<?php endif; ?> 