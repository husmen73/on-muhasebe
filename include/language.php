<?php
if(@$_SESSION["management_login"] == true)
{
	if(file_exists('theme/language/'.get_meta('', '', 'settings', 'language'))) { include_once('theme/language/'.get_meta('', '', 'settings', 'language')); }
	else if(file_exists('../theme/language/'.get_meta('', '', 'settings', 'language'))) { include_once('../theme/language/'.get_meta('', '', 'settings', 'language')); }
	else if(file_exists('../../theme/language/'.get_meta('', '', 'settings', 'language'))) { include_once('../../theme/language/'.get_meta('', '', 'settings', 'language')); }
}
else
{
	if(file_exists('theme/language/english.php')) { include_once('theme/language/english.php'); }
	else if(file_exists('../theme/language/english.php')) { include_once('../theme/language/english.php'); }
	else if(file_exists('../../theme/language/english.php')) { include_once('../../theme/language/english.php'); }	
}
?>
<?php
$lang['xxx'] = 'xxx';
function get_lang($value)
{
	global $lang;
	if (array_key_exists($value, $lang)) 
	{
    	return $lang[$value];
	}
	else
	{
		return $value;	
	}	
}

function lang($value)
{
	echo get_lang($value);	
}

?>