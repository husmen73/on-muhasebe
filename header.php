<?php session_start(); ?>
<?php ob_start(); ?>
<?php
include_once('configuration.php');
include_once('include/connect.php');
include_once('include/global.php');
include_once('include/functions.php');
include_once('include/meta.php');
include_once('include/user.php');
include_once('include/language.php');
include_once('include/product.php');
include_once('include/current.php');
include_once('include/fiche.php');
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
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.orbit.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.buttons.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.tabs.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.forms.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.tooltips.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.accordion.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.placeholder.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.foundation.alerts.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.validate.js"></script>
  <script src="<?php url('theme'); ?>/javascripts/jquery.dataTables.js"></script>
  <!-- Initialize JS Plugins -->
  <script src="<?php url('theme'); ?>/javascripts/app.js"></script>

  <!-- IE Fix for HTML5 Tags -->
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

</head>
<body>
<div class="row"> <div class="twelve columns">
	<style>
    .durdur	{
      color:#033;
      margin:-10px 10px 0 -2px;
	  position:absolute;
	  min-width:10px;
	  background-color:#F00;
    }
    </style>
<div class="row hide-on-print">
	<div class="twelve columns">
        <ul class="nav-bar">
          <li><a href="<?php url(''); ?>/index.php"><?php lang('Dashboard'); ?></a></li>
          <li class="has-flyout">
            <a href="#"><?php lang('Current'); ?></a>
            <a href="#" class="flyout-toggle"><span> </span></a>
            <ul class="flyout">
            	<li><a href="<?php url('page'); ?>/currents/add.php"><?php lang('New Current'); ?></a></li>
                <li><a href="<?php url('page'); ?>/currents/list.php"><?php lang('Current List'); ?></a></li>
            </ul>
          </li>
          <li class="has-flyout">
            <a href="#"><?php lang('Products'); ?></a>
            <a href="#" class="flyout-toggle"><span> </span></a>
            <ul class="flyout">
            	<li><a href="<?php url('page'); ?>/products/add.php"><?php lang('Add Product'); ?></a></li>
                <li><a href="<?php url('page'); ?>/products/list.php"><?php lang('Product List'); ?></a></li>
            </ul>
          </li>
          <li class="has-flyout">
            <a href="#"><?php lang('Input-Output'); ?></a>
            <a href="#" class="flyout-toggle"><span> </span></a>
            <ul class="flyout">
            	<li><a href="<?php url('page'); ?>/fiche/new.php?input"><?php lang('New Input'); ?></a></li>
                <li><a href="<?php url('page'); ?>/fiche/new.php?output"><?php lang('New Output'); ?></a></li>
                <li><a href="<?php url('page'); ?>/fiche/list.php"><?php lang('Fiche List'); ?></a></li>
            </ul>
          </li>
          <li class="has-flyout">
            <a href="#"><?php lang('Management'); ?></a>
            <a href="#" class="flyout-toggle"><span> </span></a>
            <ul class="flyout">
            	<li><a href="<?php url('page'); ?>/user/profile.php"><?php lang('Profile'); ?></a></li>
            	<li><a href="<?php url('page'); ?>/settings/settings.php"><?php lang('Settings'); ?></a></li>
              	<li><a href="<?php url('page'); ?>/user/user_management.php"><?php lang('User Management'); ?></a></li>
              	<li><a href="<?php url(''); ?>/exit.php"><?php lang('Exit'); ?></a></li>
            </ul>
          </li>
        </ul>
    </div>
</div>

<div class="row hide-on-print">
	<div class="twelve columns">
    	<div class="navigation">
            <div class="row">
                <div class="six columns">
                        <a href="<?php url(''); ?>"><?php lang('Dashboard'); ?></a> &raquo; <span id="navigation"><?php lang(''); ?></span>
                </div> <!-- /.six columns -->
                <div class="six columns text-right">
                    <a href="<?php url('page'); ?>/user/message.php?message_type=receiver"><img src="<?php url('theme'); ?>/images/icon/16/mail.png" style="margin-bottom:-4px;" /></a> <span class="label radius"><?php total_message('open', get_the_current_user('id')); ?></span>
                    <a href="<?php url('page'); ?>/user/task.php?type=receiver"><img src="<?php url('theme'); ?>/images/icon/16/clock.png" style="margin-bottom:-4px;" /></a> <span class="label radius"><?php total_task('open', get_the_current_user('id')); ?></span>
                </div> <!-- /.six columns -->
            </div> <!-- /.row -->
    	</div> <!-- /.navigation -->
	</div> <!-- /.twelve columns -->
</div> <!-- /.row -->
<p></p>

<div class="row">
	<div class="twelve columns">
    	<?php
		$page_access = true;
		$page_name = str_replace(get_url(''), '', 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
		$page_access_level	=	get_meta('', '', 'page_access', $page_name);
		if(get_the_current_user('level') > $page_access_level and $page_access_level > 0 )
		{
			alert_box('alert', get_lang('Do not have permission to access this page'));
			exit;
		}
		?>
    </div>
</div>


<?php

?>