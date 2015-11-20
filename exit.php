<?php session_start(); ?>
<?php ob_start(); ?>
<?php
include_once('configuration.php');
include_once('include/connect.php');
include_once('include/global.php');
include_once('include/meta.php');
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
    	<div class="twelve columns">
        	<?php session_destroy(); ?>
            <div class="alert-box secondary"><?php lang('Output Reset'); ?></div>
            <a href="<?php echo get_url(''); ?>/login.php" class="button bue"><?php lang('Login'); ?></a>
        </div>
    </div>