<?php


/* ----------------------------------------------
	DATABASE
---------------------------------------------- */
class database 
{
   public $prefix; // herkese açık değer
 
	function database($prefix) 
	{
		$this->users 		= $prefix.'users';
		$this->meta 		= $prefix.'meta';
		$this->message 		= $prefix.'message';
		$this->logs 		= $prefix.'logs';
		$this->products		= $prefix.'products';
		$this->currents		= $prefix.'currents';
		$this->fiche		= $prefix.'fiche';
		$this->fiche_items	= $prefix.'fiche_items';
	}
} 
$database = new database($prefix);


/* ----------------------------------------------
	CONFIG
---------------------------------------------- */
function get_config($value)
{
	if($value == 'name'){ return 'User Management';	}
	else if($value == 'datetime'){ return date("Y-m-d H:i:s");	}	
	else if($value == 'date'){ return date("Y-m-d");	}	
}

function config($value)
{
	echo get_config($value);	
}



/* ----------------------------------------------
	URL
---------------------------------------------- */
function get_url($value)
{
	global $url;
	if($value == '') { return	$url; }
	else {	return	$url.'/'.$value;	}
	
}

function url($value)
{
	echo get_url($value);	
}



/* ----------------------------------------------
	OTHER
---------------------------------------------- */

	/* ----- ALERT BOX ----- */
function alert_box($type, $message)
{
	echo '
	<div class="alert-box '.$type.'">
		'.$message.'
		<a href="" class="close">&times;</a>
	</div>
	';
}

	/* ----- SAFETY FILTER ----- */
function safety_filter($value)
{
	return trim(mysql_real_escape_string(strip_tags($value)));
}



/* ----------------------------------------------
	PAGE LOAD TIME
---------------------------------------------- */
class page_load_time
{
    function start()
    {
        global $starting;
        $mtime = microtime ();
        $mtime = explode (' ', $mtime );
        $mtime = $mtime[1] + $mtime[0];
        $starting = $mtime;
    }
    function stop()
    {
        global $starting;
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $stop = $mtime;
        $total = round (($stop - $starting), 5);
        return $total;
    }
}
$page_load_time = new page_load_time;
$page_load_time->start();


/* ----------------------------------------------
	MONEY FORMAT
---------------------------------------------- */
function is_money_format($value)
{
	if(empty($value)) { return true; }
	else
	{ 
		if(preg_match('/^[0-9]*\.?[0-9]+$/', $value)) { return true; }
		else { return false; }
	}
}


function get_mf($value)
{
	return number_format($value, 2);
}

function mf($value)
{
	echo get_mf($value);
}



/* ----------------------------------------------
	NUMBER FORMAT
---------------------------------------------- */
function get_nf($value)
{
	return round($value);
}

function nf($value)
{
	echo get_nf($value);	
}



/* ----------------------------------------------
	CHANGE NAVIGATION
---------------------------------------------- */
function change_navigation($value)
{
	echo '<script>
		$(document).ready(function () {
			$("#navigation").html(\''.$value.'\');
		});
		</script>';	
}



/* ----------------------------------------------
	GO TO PAGE
---------------------------------------------- */
function go_to_page($adress)
{
	echo '<script> window.location = "'.$adress.'"; </script>'; 
}	
?>