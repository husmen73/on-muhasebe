<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

date_default_timezone_set('Europe/Istanbul');

function alertbox($type='alert-info', $title='', $content='', $x=true)
{
	?>
	<div class="alert alert-block <?php echo $type; ?> fade in">
        <?php if($x==true):?><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php endif; ?>
        <?php if($title != ''): ?><h4><?php echo $title; ?></h4><?php endif; ?>
        <?php if($content != ''): ?><p><?php echo $content; ?></p><?php endif; ?>
    </div> <!-- /.alert -->
   <?php
}


function page_access($role)
{
	if(get_the_current_user('role') <= $role)
	{
		
	}
	else
	{
		redirect(site_url('user/no_access/'.$role));	
	}
}





function update_option($data)
{
	$ci =& get_instance();
	if(isset($data['option_group'])){ $ci->db->where('option_group', $data['option_group']); }
	$ci->db->where('option_key', $data['option_key']);
	$ci->db->delete('options');
	
	if(!isset($data['option_value2'])){$data['option_value2'] = '';}
	if(!isset($data['option_value3'])){$data['option_value3'] = '';}
	
	$ci->db->insert('options', $data);
}

function get_option($data)
{
	$ci =& get_instance();
	
	if(isset($data['option_group'])){$ci->db->where('option_group', $data['option_group']);}
	$ci->db->where('option_key', $data['option_key']);
	$query = $ci->db->get('options')->row_array();	
	if($query)
	{
		return $query;
	}
	else
	{
		return false;	
	}
}






function get_barcode($text)
{
	return base_url('plugins/barcode/barcode.php?text='.$text); 
}

function get_print_barcode($text)
{
	return site_url('general/barcode/'.replace_text_for_utf8(strtolower($text)));
}



function replace_text_for_utf8($text) {
	$text = trim($text);
	$search = array('Ç','ç','Ğ','ğ','ı','İ','Ö','ö','Ş','ş','Ü','ü',' ');
	$replace = array('c','c','g','g','i','i','o','o','s','s','u','u','-');
	$new_text = str_replace($search,$replace,$text);
	return $new_text;
}  


function get_money($value)
{
	return number_format($value,2, '.', '');
}




function add_date_time($date, $number, $type)
{
	$date = explode('-', $date);
	
	if($type=='d')
	{
		return date("Y-m-d", mktime(0, 0, 0, $date[1], $date[2]+$number, $date[0]));
	}
	else if($type=='m')
	{
		return date("Y-m-d", mktime(0, 0, 0, $date[1]+$number, $date[2], $date[0]));
	}
	else if($type=='Y')
	{
		return date("Y-m-d", mktime(0, 0, 0, $date[1], $date[2], $date[0]+$number));
	}
}




function days_left($date)
{
	$date1 = strtotime(date('Y-m-d')); 
	$date2 = strtotime(substr($date,0,10)); 
	$day = ($date1-$date2)/86400 ; 
	return str_replace('-', '', round($day)); 
}



function hours_left($date)
{
	$date1 = strtotime(date('Y-m-d H:i:s'));
	$date2 = strtotime($date); 
	$day = ($date2-$date1)/3600 ; 
	return str_replace('', '', round($day)); 
}



function days_late($date)
{
	$date1 = strtotime(date('Y-m-d H:i:s'));
	$date2 = strtotime($date); 
	$day = (($date1-$date2)/3600)/24 ; 
	return str_replace('', '', round($day)); 
}



function excel_reader($inputFileName)
{
	/** PHPExcel_IOFactory */
	include 'plugins/excel_reader/Classes/PHPExcel/IOFactory.php';
	
	
	//echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
	try {
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	} catch(Exception $e) {
		die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	}
	
	return $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
}

?>