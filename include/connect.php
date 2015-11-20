<?php
if($db_name == '')
{
	echo '<script> window.location = "installation.php"; </script>';
}
/* ----------------------------------------------
	MYSQL CONNTECT
---------------------------------------------- */ 
$db_connect = @mysql_connect($db_host,$db_user_name,$db_password);
if(!$db_connect) 
{
	echo'<p></p><div class="row"><div class="alert-box alert">No connection to the server.</div></div>';
	exit;
}
$db_select = mysql_select_db($db_name);
if(!$db_select){ echo '<p></p><div class="row"><div class="alert-box alert">The database was not found.</div></div>'; exit; }
?>